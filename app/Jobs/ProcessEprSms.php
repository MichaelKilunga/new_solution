<?php

namespace App\Jobs;

use App\Models\AiLog;
use App\Services\EprCommandHandler;
use App\Services\LanguageDetector;
use App\Services\ModerationService;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessEprSms implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $from,
        public string $text,
        public ?string $messageId = null
    ) {}

    public function handle(
        ModerationService $moderation,
        LanguageDetector $languageDetector,
        EprCommandHandler $commandHandler,
        SmsService $smsService
    ): void {
        Log::info('ProcessEprSms Job Started', ['from' => $this->from, 'text' => $this->text]);

        $lang = $languageDetector->detect($this->text);

        // 1. Proactive Moderation & Abuse Check
        if ($moderation->checkAbuse($this->text)) {
            $abuseResult = $moderation->handleAbuseAttempt($this->from, $lang);
            $smsService->sendSms($this->from, $abuseResult['warning_message']);

            AiLog::create([
                'phone_number' => $this->from,
                'channel' => 'SMS',
                'query' => $this->text,
                'response' => $abuseResult['warning_message'],
                'model' => 'moderation-blocked',
            ]);

            return;
        }

        // 2. Business Protocol & RAG Execution
        $handled = $commandHandler->handle($this->from, $this->text);
        $responseText = $handled['response'];
        $meta = $handled['data'] ?? [];

        // 3. Log Telemetry to ai_logs
        AiLog::create([
            'phone_number' => $this->from,
            'channel' => 'SMS',
            'query' => $this->text,
            'response' => $responseText,
            'prompt_tokens' => $meta['prompt_tokens'] ?? 0,
            'completion_tokens' => $meta['completion_tokens'] ?? 0,
            'total_tokens' => $meta['total_tokens'] ?? 0,
            'model' => $meta['model'] ?? $handled['command_type'],
        ]);

        // 4. Outbound SMS Dispatch via Africa's Talking
        $smsService->sendSms($this->from, $responseText);
    }
}
