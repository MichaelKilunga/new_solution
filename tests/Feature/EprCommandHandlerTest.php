<?php

namespace Tests\Feature;

use App\Services\EprCommandHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EprCommandHandlerTest extends TestCase
{
    use RefreshDatabase;

    protected EprCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->handler = app(EprCommandHandler::class);
    }

    public function test_recycler_lookup_command(): void
    {
        $res = $this->handler->handle('0712345678', 'HURU RECYCLER Temeke PET');

        $this->assertEquals('RECYCLER_LOOKUP', $res['command_type']);
        $this->assertStringContainsString('Temeke', $res['response']);
        $this->assertStringNotContainsString('*', $res['response']);
    }

    public function test_producer_fee_calculator_command(): void
    {
        $res = $this->handler->handle('0712345678', 'HURU FEE PET safi tani 10');

        $this->assertEquals('PRODUCER_FEE', $res['command_type']);
        $this->assertStringContainsString('Ada ya Producer', $res['response']);
        $this->assertDatabaseHas('producer_declarations', [
            'material_category' => 'Tier 1',
            'quarterly_tonnage' => 10,
        ]);
    }

    public function test_regulation_check_command(): void
    {
        $res = $this->handler->handle('0712345678', 'HURU REGULATION mifuniko ya chupa');

        $this->assertEquals('REGULATION_CHECK', $res['command_type']);
        $this->assertStringContainsString('Kanuni', $res['response']);
    }

    public function test_leakage_report_command(): void
    {
        $res = $this->handler->handle('0712345678', 'HURU RIPOTI Ilala Daraja la Msimbazi limejaa chupa');

        $this->assertEquals('LEAKAGE_REPORT', $res['command_type']);
        $this->assertStringContainsString('W-', $res['response']);
        $this->assertDatabaseHas('waste_leakage_reports', [
            'sender_phone' => '0712345678',
            'district' => 'Ilala',
        ]);
    }
}
