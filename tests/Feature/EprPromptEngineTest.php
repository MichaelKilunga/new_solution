<?php

namespace Tests\Feature;

use App\Services\EprPromptEngine;
use App\Services\LanguageDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EprPromptEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_prompt_engine_builds_context_and_enforces_zero_asterisks(): void
    {
        $this->seed();
        $engine = app(EprPromptEngine::class);

        $prompt = $engine->buildPrompt('Jinsi ya kurejeleza chupa za PET Temeke', 'sw');

        $this->assertStringContainsString('STRICT CONSTRAINTS', $prompt);
        $this->assertStringContainsString('OFFICIAL KNOWLEDGE CONTEXT', $prompt);

        $sanitized = $engine->sanitizeForSms("Header\n* Item 1\n**Bold Text**");
        $this->assertStringNotContainsString('*', $sanitized);
    }

    public function test_language_detector_identifies_swahili(): void
    {
        $detector = app(LanguageDetector::class);

        $this->assertEquals('sw', $detector->detect('Habari gani kuhusu vituo vya taka Temeke'));
        $this->assertEquals('en', $detector->detect('Where can I find plastic recycling centers'));
    }
}
