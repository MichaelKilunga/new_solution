<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProducerDeclarationTest extends TestCase
{
    use RefreshDatabase;

    public function test_producer_declaration_submission_calculates_eco_fee(): void
    {
        $response = $this->postJson('/api/producer/declaration', [
            'company_name' => 'Safari Water Ltd',
            'tin_number' => '100-200-300',
            'packaging_type' => '500ml Clear PET',
            'material_category' => 'Tier 1 (Clear PET)',
            'quarterly_tonnage' => 10,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'declaration' => [
                    'company_name' => 'Safari Water Ltd',
                    'calculated_eco_fee' => '150000.00',
                ],
            ]);

        $this->assertDatabaseHas('producer_declarations', [
            'company_name' => 'Safari Water Ltd',
            'tin_number' => '100-200-300',
        ]);
    }
}
