<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $username;

    protected string $apiKey;

    protected string $shortcode;

    public function __construct()
    {
        $this->username = (string) (config('services.africastalking.username') ?? env('AT_USERNAME') ?? 'sandbox');
        $this->apiKey = (string) (config('services.africastalking.key') ?? env('AT_API_KEY') ?? '');
        $this->shortcode = (string) (config('services.africastalking.from') ?? env('AT_FROM') ?? env('AT_SHORTCODE') ?? '15054');
    }

    /**
     * Send Outbound SMS message.
     *
     * @return array{success: bool, message: string, response: mixed}
     */
    public function sendSms(string $to, string $message): array
    {
        // Enforce strict plain text for Africa's Talking outbound SMS
        $cleanMessage = str_replace(['*', '#', '`', '_'], '', $message);

        if (empty($this->apiKey) || $this->apiKey === 'mock-key') {
            Log::info("SMS Outbound (Simulated) -> To: {$to} | Msg: {$cleanMessage}");

            return [
                'success' => true,
                'message' => 'Simulated SMS dispatch',
                'response' => ['status' => 'simulated', 'to' => $to, 'message' => $cleanMessage],
            ];
        }

        $domain = ($this->username === 'sandbox') ? 'api.sandbox.africastalking.com' : 'api.africastalking.com';
        $url = "https://{$domain}/version1/messaging";

        try {
            $response = Http::asForm()->withHeaders([
                'apiKey' => $this->apiKey,
                'Accept' => 'application/json',
            ])->post($url, [
                'username' => $this->username,
                'to' => $to,
                'message' => $cleanMessage,
                'from' => $this->shortcode,
            ]);

            if ($response->successful()) {
                Log::info("SMS Sent Successfully to {$to}");

                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'response' => $response->json(),
                ];
            }

            Log::error('SMS Dispatch Failed', ['status' => $response->status(), 'body' => $response->body()]);
        } catch (\Throwable $e) {
            Log::error('SMS Exception', ['error' => $e->getMessage()]);
        }

        return [
            'success' => false,
            'message' => 'Failed to send SMS',
            'response' => null,
        ];
    }
}
