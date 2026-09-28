<?php

namespace App\Services;

use App\Models\EprKnowledgeBase;
use App\Models\FieldProvider;

class EprPromptEngine
{
    /**
     * Build the RAG system prompt with database context and strict SMS formatting constraints.
     */
    public function buildPrompt(string $userQuery, string $language = 'sw'): string
    {
        $keywords = $this->extractKeywords($userQuery);
        $knowledgeContext = $this->fetchKnowledgeContext($keywords, $language);
        $providersContext = $this->fetchProvidersContext($userQuery);

        $targetLangName = ($language === 'sw') ? 'Swahili' : 'English';

        $systemPrompt = "You are EcoHuru, the official Extended Producer Responsibility (EPR) guidance assistant in Tanzania.\n"
            ."Provide direct, factual guidance based strictly on the provided context.\n\n"
            ."STRICT CONSTRAINTS:\n"
            ."1. Produce STRICT PLAIN TEXT ONLY. Never use asterisks (*), hashtags (#), or any markdown formatting.\n"
            ."2. Keep the entire response under 160 words so it fits in a single SMS message.\n"
            .'3. Output MUST be in '.$targetLangName.".\n"
            ."4. Include phone numbers and TZS buyback prices when mentioning recycling hubs.\n\n"
            ."OFFICIAL KNOWLEDGE CONTEXT:\n".($knowledgeContext ?: 'No specific regulations matched.')."\n\n"
            ."RECYCLERS & PRO DIRECTORY:\n".($providersContext ?: 'No specific recyclers matched.')."\n\n"
            .'USER QUERY: '.$userQuery;

        return $systemPrompt;
    }

    /**
     * Clean and format AI response to guarantee plain-text SMS formatting.
     */
    public function sanitizeForSms(string $text): string
    {
        // Strip out asterisks, markdown headers, and formatting artifacts
        $cleaned = str_replace(['*', '#', '`', '_', '~'], '', $text);

        // Normalize multiple spaces and line breaks
        $cleaned = preg_replace('/[ \t]+/', ' ', $cleaned);
        $cleaned = preg_replace('/\n{3,}/', "\n\n", $cleaned);

        return trim($cleaned);
    }

    /**
     * Extract key tokens from query string.
     */
    public function extractKeywords(string $query): array
    {
        $cleaned = strtolower(preg_replace('/[^\w\s]/u', ' ', $query));
        $stopWords = ['na', 'ya', 'wa', 'za', 'kwa', 'katika', 'hapa', 'je', 'gani', 'the', 'is', 'in', 'at', 'of', 'and', 'or', 'for', 'to'];
        $words = array_filter(explode(' ', $cleaned));

        return array_values(array_diff($words, $stopWords));
    }

    /**
     * Fetch relevant knowledge base articles matching keywords.
     */
    protected function fetchKnowledgeContext(array $keywords, string $language = 'sw'): string
    {
        if (empty($keywords)) {
            $articles = EprKnowledgeBase::where('is_active', true)
                ->where('language', $language)
                ->take(3)
                ->get();
        } else {
            $query = EprKnowledgeBase::where('is_active', true)
                ->where('language', $language);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('keywords', 'LIKE', "%{$kw}%")
                        ->orWhere('title', 'LIKE', "%{$kw}%")
                        ->orWhere('content', 'LIKE', "%{$kw}%");
                }
            });

            $articles = $query->take(3)->get();
        }

        if ($articles->isEmpty()) {
            // Fallback to active articles in language
            $articles = EprKnowledgeBase::where('is_active', true)
                ->where('language', $language)
                ->take(2)
                ->get();
        }

        return $articles->map(fn ($a) => "[{$a->category}] {$a->title}: {$a->content}")->implode("\n");
    }

    /**
     * Fetch provider directory matched by region, district or material.
     */
    protected function fetchProvidersContext(string $query): string
    {
        $lowerQuery = strtolower($query);

        $providers = FieldProvider::where('is_verified', true)->get();

        $matched = $providers->filter(function ($p) use ($lowerQuery) {
            return str_contains($lowerQuery, strtolower($p->district))
                || str_contains($lowerQuery, strtolower($p->region))
                || str_contains($lowerQuery, strtolower($p->provider_type))
                || str_contains($lowerQuery, strtolower($p->accepted_materials));
        });

        if ($matched->isEmpty()) {
            $matched = $providers->take(3);
        }

        return $matched->map(function ($p) {
            $rates = $p->buyback_rates_json ? " Rates: {$p->buyback_rates_json}" : '';

            return "{$p->name} ({$p->provider_type}, {$p->district}): Phone {$p->phone}. Materials: {$p->accepted_materials}.{$rates}";
        })->implode("\n");
    }
}
