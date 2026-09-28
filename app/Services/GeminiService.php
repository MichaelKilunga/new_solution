<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY', ''));
        $this->model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-2.5-flash'));
    }

    /**
     * Generate AI text guidance based on prompt.
     *
     * @return array{text: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, model: string}
     */
    public function generate(string $prompt, string $fallbackContext = ''): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'mock-key') {
            return $this->generateFallbackResponse($prompt, $fallbackContext);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(12)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'maxOutputTokens' => 300,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $usage = $data['usageMetadata'] ?? [];

                return [
                    'text' => trim($text),
                    'prompt_tokens' => $usage['promptTokenCount'] ?? 0,
                    'completion_tokens' => $usage['candidatesTokenCount'] ?? 0,
                    'total_tokens' => $usage['totalTokenCount'] ?? 0,
                    'model' => $this->model,
                ];
            }

            Log::warning('Gemini API Error Response', ['status' => $response->status(), 'body' => $response->body()]);
        } catch (\Throwable $e) {
            Log::error('Gemini API Exception', ['error' => $e->getMessage()]);
        }

        return $this->generateFallbackResponse($prompt, $fallbackContext);
    }

    /**
     * Local RAG response fallback when API key is unconfigured or offline.
     */
    protected function generateFallbackResponse(string $prompt, string $fallbackContext): array
    {
        // Extract relevant lines from RAG context
        $lines = array_filter(explode("\n", $prompt));
        $contextLines = [];

        foreach ($lines as $line) {
            if (str_contains($line, 'OFFICIAL KNOWLEDGE CONTEXT') || str_contains($line, 'RECYCLERS & PRO DIRECTORY')) {
                continue;
            }
            if (str_contains($line, '[')) {
                $contextLines[] = $line;
            }
        }

        $fallbackText = ! empty($contextLines)
            ? implode(' ', array_slice($contextLines, 0, 3))
            : 'EcoHuru EPR: Tafadhali tumia maneno ya mkato kama EPR RECYCLER [District] au EPR FEE kupata maelezo na ada halisi za urejelezaji.';

        // Clean out any formatting
        $fallbackText = str_replace(['*', '#', '`', '_'], '', $fallbackText);

        return [
            'text' => trim($fallbackText),
            'prompt_tokens' => 50,
            'completion_tokens' => 50,
            'total_tokens' => 100,
            'model' => $this->model.'-local-rag',
        ];
    }
}
