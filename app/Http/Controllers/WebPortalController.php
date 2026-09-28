<?php

namespace App\Http\Controllers;

use App\Models\AiLog;
use App\Models\FieldProvider;
use App\Models\ProducerDeclaration;
use App\Models\WasteLeakageReport;
use App\Services\EprCommandHandler;
use App\Services\LanguageDetector;
use App\Services\ModerationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebPortalController extends Controller
{
    public function __construct(
        protected EprCommandHandler $commandHandler,
        protected ModerationService $moderation,
        protected LanguageDetector $languageDetector
    ) {}

    /**
     * Render the main EcoHuru EPR Web Dashboard & Interactive Portal.
     */
    public function index(): View
    {
        $metrics = $this->getMetricsData();
        $recyclers = FieldProvider::where('is_verified', true)->orderBy('region')->get();
        $recentReports = WasteLeakageReport::latest()->take(10)->get();
        $recentDeclarations = ProducerDeclaration::latest()->take(10)->get();

        return view('dashboard', compact('metrics', 'recyclers', 'recentReports', 'recentDeclarations'));
    }

    /**
     * Web Chat REST API Endpoint
     */
    public function webChat(Request $request): JsonResponse
    {
        $query = $request->input('message', '');
        $phone = $request->input('phone', 'WEB-USER-'.rand(100, 999));

        if (empty($query)) {
            return response()->json(['error' => 'Message is required'], 400);
        }

        $lang = $this->languageDetector->detect($query);

        if ($this->moderation->checkAbuse($query)) {
            $abuseResult = $this->moderation->handleAbuseAttempt($phone, $lang);

            return response()->json([
                'response' => $abuseResult['warning_message'],
                'command_type' => 'BLOCKED',
            ]);
        }

        $result = $this->commandHandler->handle($phone, $query);

        // Log web query to ai_logs
        AiLog::create([
            'phone_number' => $phone,
            'channel' => 'WEB',
            'query' => $query,
            'response' => $result['response'],
            'prompt_tokens' => $result['data']['prompt_tokens'] ?? 0,
            'completion_tokens' => $result['data']['completion_tokens'] ?? 0,
            'total_tokens' => $result['data']['total_tokens'] ?? 0,
            'model' => $result['data']['model'] ?? 'web-rag',
        ]);

        return response()->json([
            'response' => $result['response'],
            'command_type' => $result['command_type'],
            'data' => $result['data'],
        ]);
    }

    /**
     * Store Producer Eco-Fee Compliance Declaration
     */
    public function storeDeclaration(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'tin_number' => 'required|string|max:50',
            'packaging_type' => 'required|string|max:100',
            'material_category' => 'required|string|max:100',
            'quarterly_tonnage' => 'required|numeric|min:0.1',
            'pro_membership_id' => 'nullable|string|max:100',
        ]);

        // Calculate Eco-Fee based on category
        $ratePerTon = match (strtoupper($validated['material_category'])) {
            'TIER 1', 'TIER 1 (CLEAR PET)' => 15000,
            'TIER 2', 'TIER 2 (COLORED PET/HDPE)' => 25000,
            'TIER 3', 'TIER 3 (MULTILAYER)' => 50000,
            default => 25000,
        };

        $calculatedFee = $validated['quarterly_tonnage'] * $ratePerTon;

        $declaration = ProducerDeclaration::create([
            'company_name' => $validated['company_name'],
            'tin_number' => $validated['tin_number'],
            'packaging_type' => $validated['packaging_type'],
            'material_category' => $validated['material_category'],
            'quarterly_tonnage' => $validated['quarterly_tonnage'],
            'calculated_eco_fee' => $calculatedFee,
            'pro_membership_id' => $validated['pro_membership_id'] ?? null,
            'status' => 'SUBMITTED',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Declaration submitted successfully',
            'declaration' => $declaration,
        ], 201);
    }

    /**
     * Store Community Waste Leakage Report via Web
     */
    public function storeLeakageReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sender_phone' => 'required|string|max:50',
            'district' => 'required|string|max:100',
            'location_details' => 'required|string',
            'description' => 'required|string',
        ]);

        $reportCode = '#W-'.rand(100, 999);

        $report = WasteLeakageReport::create([
            'report_code' => $reportCode,
            'sender_phone' => $validated['sender_phone'],
            'district' => $validated['district'],
            'location_details' => $validated['location_details'],
            'description' => $validated['description'],
            'status' => 'PENDING',
            'assigned_to' => "{$validated['district']} Municipal Environmental Officer",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leakage report created successfully',
            'report' => $report,
        ], 201);
    }

    /**
     * Fetch Live Dashboard Telemetry Metrics
     */
    public function dashboardMetrics(): JsonResponse
    {
        return response()->json($this->getMetricsData());
    }

    protected function getMetricsData(): array
    {
        return [
            'total_inquiries' => AiLog::count(),
            'total_sms_inquiries' => AiLog::where('channel', 'SMS')->count(),
            'total_web_inquiries' => AiLog::where('channel', 'WEB')->count(),
            'total_leakage_reports' => WasteLeakageReport::count(),
            'pending_leakage_reports' => WasteLeakageReport::where('status', 'PENDING')->count(),
            'resolved_leakage_reports' => WasteLeakageReport::where('status', 'RESOLVED')->count(),
            'total_declarations' => ProducerDeclaration::count(),
            'total_eco_fees_tzs' => ProducerDeclaration::sum('calculated_eco_fee'),
            'total_declared_tonnage' => ProducerDeclaration::sum('quarterly_tonnage'),
            'verified_recyclers_count' => FieldProvider::where('is_verified', true)->count(),
        ];
    }
}
