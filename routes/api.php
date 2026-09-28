<?php

use App\Http\Controllers\SmsController;
use App\Http\Controllers\WebPortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EcoHuru EPR Platform API Routes
|--------------------------------------------------------------------------
*/

// Telecom Inbound SMS Webhook (Africa's Talking Shortcode 15054)
Route::post('/sms/inbound', [SmsController::class, 'inbound']);

// Web Chat REST API
Route::post('/chat', [WebPortalController::class, 'webChat']);

// Producer Compliance Declaration Submission
Route::post('/producer/declaration', [WebPortalController::class, 'storeDeclaration']);

// Community Waste Leakage Report Submission
Route::post('/leakage-report', [WebPortalController::class, 'storeLeakageReport']);

// Telemetry & Dashboard Analytics Endpoint
Route::get('/metrics', [WebPortalController::class, 'dashboardMetrics']);
