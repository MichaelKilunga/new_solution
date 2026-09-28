<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ModerationService
{
    protected array $blockedKeywords = [
        'fuck', 'shit', 'bitch', 'asshole', 'bastard',
        'matusi', 'kuma', 'mboo', 'pumbavu', 'fala', 'jinga', 'shit',
    ];

    /**
     * Check if content contains abusive language.
     */
    public function checkAbuse(string $text): bool
    {
        $lower = strtolower($text);
        foreach ($this->blockedKeywords as $keyword) {
            if (str_contains($lower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Record abuse attempt for phone number and return warning/status.
     *
     * @return array{is_blocked: bool, warning_message: string|null, abuse_count: int}
     */
    public function handleAbuseAttempt(string $phone, string $lang = 'sw'): array
    {
        $cacheKey = "abuse_count_{$phone}";
        $count = Cache::get($cacheKey, 0) + 1;
        Cache::put($cacheKey, $count, now()->addDays(30));

        if ($count >= 3) {
            $message = ($lang === 'sw')
                ? 'Namba yako imefungiwa kwa kutuma ujumbe usiolimika au wenye matusi. Wasiliana na NEMC.'
                : 'Your phone number has been suspended for repeated violations. Contact NEMC support.';

            return [
                'is_blocked' => true,
                'warning_message' => $message,
                'abuse_count' => $count,
            ];
        }

        $message = ($lang === 'sw')
            ? "Onyo ({$count}/3): Ujumbe wako una maneno yasiyofaa. Tafadhali tumia lugha ya heshima kuhusu EPR na mazingira."
            : "Warning ({$count}/3): Improper content detected. Please use respectful language regarding EPR.";

        return [
            'is_blocked' => false,
            'warning_message' => $message,
            'abuse_count' => $count,
        ];
    }
}
