<?php

namespace App\Services;

use App\Models\EprKnowledgeBase;
use App\Models\FieldProvider;
use App\Models\ProducerDeclaration;
use App\Models\WasteLeakageReport;

class EprCommandHandler
{
    public function __construct(
        protected EprPromptEngine $promptEngine,
        protected GeminiService $geminiService,
        protected LanguageDetector $languageDetector
    ) {}

    /**
     * Process inbound SMS command payload.
     *
     * @return array{response: string, command_type: string, data: mixed}
     */
    public function handle(string $senderPhone, string $rawBody): array
    {
        // Strip out 'HURU' or 'EPR' keyword if present at the beginning
        $cleanBody = trim(preg_replace('/^(HURU|EPR)\s+/i', '', trim($rawBody)));
        $lang = $this->languageDetector->detect($cleanBody);

        $parts = preg_split('/\s+/', $cleanBody);
        $firstWord = strtoupper($parts[0] ?? '');

        return match ($firstWord) {
            'RECYCLER', 'VITUO', 'RECYCLERS' => $this->handleRecyclerLookup(array_slice($parts, 1), $lang),
            'FEE', 'ADA', 'TARIFF' => $this->handleProducerFeeCalc(array_slice($parts, 1), $cleanBody, $lang),
            'REGULATION', 'KANUNI', 'SHERIA' => $this->handleRegulationCheck(array_slice($parts, 1), $cleanBody, $lang),
            'RIPOTI', 'REPORT', 'TAARIFA' => $this->handleLeakageReport($senderPhone, array_slice($parts, 1), $cleanBody, $lang),
            default => $this->handleGeneralQuery($cleanBody, $lang),
        };
    }

    /**
     * Command 1: Recycler & Buyback Price Lookup
     */
    protected function handleRecyclerLookup(array $args, string $lang): array
    {
        $district = $args[0] ?? '';
        $material = strtoupper($args[1] ?? 'PET');

        $query = FieldProvider::where('is_verified', true);

        if (! empty($district)) {
            $query->where(function ($q) use ($district) {
                $q->where('district', 'LIKE', "%{$district}%")
                    ->orWhere('region', 'LIKE', "%{$district}%")
                    ->orWhere('ward', 'LIKE', "%{$district}%");
            });
        }

        $hubs = $query->take(3)->get();

        if ($hubs->isEmpty()) {
            $hubs = FieldProvider::where('is_verified', true)->take(2)->get();
        }

        $hubDetails = [];
        $i = 1;
        foreach ($hubs as $hub) {
            $rates = $hub->buyback_rates;
            $rateStr = isset($rates[$material]) ? "TZS {$rates[$material]}/kg" : 'Bei nzuri';
            $hubDetails[] = "{$i}. {$hub->name} ({$hub->phone}) {$rateStr}";
            $i++;
        }

        $locationLabel = ! empty($district) ? ucfirst(strtolower($district)) : 'nchini';
        $listStr = implode('. ', $hubDetails);

        $response = ($lang === 'sw')
            ? "Vituo vya {$material} {$locationLabel}: {$listStr}. Tenganisha chupa safi na zisizo na vifuniko kwa bei ya juu."
            : "{$material} Recycling Hubs {$locationLabel}: {$listStr}. Separate clean bottles and remove caps for Grade A buyback prices.";

        return [
            'response' => $this->promptEngine->sanitizeForSms($response),
            'command_type' => 'RECYCLER_LOOKUP',
            'data' => ['district' => $district, 'material' => $material, 'count' => $hubs->count()],
        ];
    }

    /**
     * Command 2: Producer Eco-Fee & Eco-Design Calculator
     */
    protected function handleProducerFeeCalc(array $args, string $fullBody, string $lang): array
    {
        $tonnage = 1.0;
        $material = 'PET';
        $isClear = ! str_contains(strtolower($fullBody), 'color') && ! str_contains(strtolower($fullBody), 'rangi');

        foreach ($args as $arg) {
            if (is_numeric($arg)) {
                $val = (float) $arg;
                if ($val > 0) {
                    $tonnage = $val;
                }
            } elseif (in_array(strtoupper($arg), ['PET', 'HDPE', 'LDPE', 'PP', 'MULTILAYER'])) {
                $material = strtoupper($arg);
            }
        }

        $tier = 1;
        $ratePerTon = 15000;

        if ($material === 'MULTILAYER') {
            $tier = 3;
            $ratePerTon = 50000;
        } elseif (! $isClear || in_array($material, ['HDPE', 'PP'])) {
            $tier = 2;
            $ratePerTon = 25000;
        }

        $calculatedFee = $tonnage * $ratePerTon;
        $potentialSavings = ($tier > 1) ? ($tonnage * ($ratePerTon - 15000)) : 0;

        ProducerDeclaration::create([
            'company_name' => 'SMS Inquiry Producer',
            'tin_number' => 'PENDING-'.rand(1000, 9999),
            'packaging_type' => "{$material} Bottle",
            'material_category' => "Tier {$tier}",
            'quarterly_tonnage' => $tonnage,
            'calculated_eco_fee' => $calculatedFee,
            'status' => 'ESTIMATED',
        ]);

        $feeFormatted = number_format($calculatedFee);
        $savingsFormatted = number_format($potentialSavings);

        if ($lang === 'sw') {
            $savingsMsg = ($potentialSavings > 0)
                ? " Badili vifuniko au rangi kuwa safi kuokoa TZS {$savingsFormatted}."
                : '';
            $response = "Chupa za {$material} zipo Tier {$tier}. Ada ya Producer: TZS {$feeFormatted} kwa tani {$tonnage}.{$savingsMsg} Mawasiliano ya PRO: 0713-999-888.";
        } else {
            $savingsMsg = ($potentialSavings > 0)
                ? " Switch to clear uncolored PET to save TZS {$savingsFormatted}."
                : '';
            $response = "{$material} packaging is classified as Tier {$tier}. Estimated Producer Eco-Fee: TZS {$feeFormatted} for {$tonnage} tons.{$savingsMsg} PRO Helpline: 0713-999-888.";
        }

        return [
            'response' => $this->promptEngine->sanitizeForSms($response),
            'command_type' => 'PRODUCER_FEE',
            'data' => ['tier' => $tier, 'fee' => $calculatedFee, 'tonnage' => $tonnage],
        ];
    }

    /**
     * Command 3: Small Business Regulatory Check
     */
    protected function handleRegulationCheck(array $args, string $fullBody, string $lang): array
    {
        $article = EprKnowledgeBase::where('is_active', true)
            ->where('category', 'REGULATION')
            ->where('language', $lang)
            ->first();

        if ($article) {
            $response = $article->content;
        } else {
            $response = ($lang === 'sw')
                ? 'Kulingana na Kanuni za Mazingira za 2022, mifuniko ya plastiki yenye nembo ya ziada (cap seals) imepigwa marufuku. Tumia neck rings zilizoidhinishwa na TBS/NEMC kuepuka faini.'
                : 'According to 2022 Plastic Bottle Cap Regulations, secondary shrink cap seals are strictly prohibited in Tanzania. Use NEMC/TBS approved neck rings.';
        }

        return [
            'response' => $this->promptEngine->sanitizeForSms($response),
            'command_type' => 'REGULATION_CHECK',
            'data' => ['query' => $fullBody],
        ];
    }

    /**
     * Command 4: Community Waste Leakage Reporting
     */
    protected function handleLeakageReport(string $phone, array $args, string $fullBody, string $lang): array
    {
        $district = ucfirst(strtolower($args[0] ?? 'Ilala'));
        $details = implode(' ', array_slice($args, 1));
        if (empty($details)) {
            $details = $fullBody;
        }

        $reportCode = 'W-'.rand(100, 999);

        $report = WasteLeakageReport::create([
            'report_code' => $reportCode,
            'sender_phone' => $phone,
            'district' => $district,
            'location_details' => $district,
            'description' => $details,
            'status' => 'PENDING',
            'assigned_to' => "{$district} Municipal Environmental Officer",
        ]);

        if ($lang === 'sw') {
            $response = "Taarifa yako ya taka za plastiki {$district} imepokelewa (Kumb: {$reportCode}). Afisa Mazingira na Kikundi cha Watoza Taka wametaarifiwa. Ahsante kwa kulinda mazingira!";
        } else {
            $response = "Your plastic waste report for {$district} has been registered (Ref: {$reportCode}). Municipal Environmental Officer and local Recyclers have been notified. Thank you!";
        }

        return [
            'response' => $this->promptEngine->sanitizeForSms($response),
            'command_type' => 'LEAKAGE_REPORT',
            'data' => ['report_code' => $reportCode, 'report_id' => $report->id],
        ];
    }

    /**
     * General RAG Fallback Query using Gemini & PromptEngine
     */
    protected function handleGeneralQuery(string $query, string $lang): array
    {
        $systemPrompt = $this->promptEngine->buildPrompt($query, $lang);
        $result = $this->geminiService->generate($systemPrompt, $query);

        $cleanResponse = $this->promptEngine->sanitizeForSms($result['text']);

        return [
            'response' => $cleanResponse,
            'command_type' => 'GENERAL_RAG',
            'data' => [
                'prompt_tokens' => $result['prompt_tokens'],
                'completion_tokens' => $result['completion_tokens'],
                'total_tokens' => $result['total_tokens'],
                'model' => $result['model'],
            ],
        ];
    }
}
