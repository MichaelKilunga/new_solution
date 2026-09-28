<?php

namespace App\Http\Controllers;

use App\Models\FieldProvider;
use App\Models\ProducerDeclaration;
use App\Models\WasteLeakageReport;
use Illuminate\View\View;

class PublicPortalController extends Controller
{
    /**
     * Render the Public Information & Services Portal.
     */
    public function index(): View
    {
        $recyclers = FieldProvider::where('is_verified', true)->orderBy('region')->get();
        $recentReports = WasteLeakageReport::latest()->take(5)->get();
        $recentDeclarations = ProducerDeclaration::latest()->take(5)->get();

        return view('welcome', compact('recyclers', 'recentReports', 'recentDeclarations'));
    }
}
