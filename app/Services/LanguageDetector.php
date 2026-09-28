<?php

namespace App\Services;

class LanguageDetector
{
    /**
     * Common Swahili words & tokens used in waste & recycling queries.
     */
    protected array $swahiliTokens = [
        'habari', 'nini', 'jinsi', 'vituo', 'bei', 'taka', 'chupa', 'vifuniko',
        'mifuniko', 'plastiki', 'mzalishaji', 'ada', 'kanuni', 'ripoti', 'wapi',
        'kwa', 'ya', 'wa', 'za', 'la', 'na', 'katika', 'rafiki', 'mazingira',
        'watoza', 'rejelea', 'urejelezaji', 'hapa', 'ilala', 'temeke', 'kinondoni',
        'mbeya', 'arusha', 'mwanza', 'dodoma', 'ahsante', 'taarifa', 'akiba',
        'safi', 'tani', 'taka', 'mifuko', 'marufuku', 'sheria', 'pamoja', 'hadi',
    ];

    /**
     * Detect if text is Swahili or English.
     */
    public function detect(string $text): string
    {
        $cleanText = strtolower(preg_replace('/[^\w\s]/u', ' ', $text));
        $words = array_filter(explode(' ', $cleanText));

        if (empty($words)) {
            return 'sw'; // Default to Swahili for Tanzanian context
        }

        $swCount = 0;
        foreach ($words as $word) {
            if (in_array($word, $this->swahiliTokens)) {
                $swCount++;
            }
        }

        // If at least one distinct Swahili indicator is present, classify as Swahili
        return ($swCount > 0) ? 'sw' : 'en';
    }
}
