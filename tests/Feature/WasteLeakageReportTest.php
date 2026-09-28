<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WasteLeakageReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_waste_leakage_report_submission_creates_record(): void
    {
        $response = $this->postJson('/api/leakage-report', [
            'sender_phone' => '0754111222',
            'district' => 'Temeke',
            'location_details' => 'Mkazinga River Bridge',
            'description' => 'Accumulated PET plastic bottles blocking drainage',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('waste_leakage_reports', [
            'sender_phone' => '0754111222',
            'district' => 'Temeke',
            'status' => 'PENDING',
        ]);
    }
}
