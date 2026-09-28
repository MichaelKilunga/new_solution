<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEprSms;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    /**
     * Africa's Talking HTTP POST Inbound SMS Webhook
     */
    public function inbound(Request $request): JsonResponse
    {
        $from = $request->input('from', $request->input('phoneNumber', ''));
        $text = $request->input('text', $request->input('message', ''));
        $messageId = $request->input('id', $request->input('messageId', ''));

        Log::info('Inbound SMS Webhook Received', ['from' => $from, 'text' => $text, 'id' => $messageId]);

        if (empty($from) || empty($text)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing from or text parameter',
            ], 400);
        }

        // Dispatch background queue job instantly to avoid gateway handshake timeout
        ProcessEprSms::dispatch($from, $text, $messageId);

        return response()->json([
            'status' => 'success',
            'message' => 'SMS queued successfully',
        ], 200);
    }
}
