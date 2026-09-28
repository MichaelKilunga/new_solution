<?php

namespace Tests\Feature;

use App\Jobs\ProcessEprSms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SmsWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_inbound_sms_webhook_dispatches_job_and_returns_200(): void
    {
        Queue::fake();

        $response = $this->postJson('/api/sms/inbound', [
            'from' => '255712345678',
            'text' => 'EPR RECYCLER Temeke PET',
            'id' => 'ATXid_12345',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'SMS queued successfully',
            ]);

        Queue::assertPushed(ProcessEprSms::class, function ($job) {
            return $job->from === '255712345678' && $job->text === 'EPR RECYCLER Temeke PET';
        });
    }

    public function test_inbound_sms_webhook_rejects_missing_params(): void
    {
        $response = $this->postJson('/api/sms/inbound', []);

        $response->assertStatus(400)
            ->assertJson(['status' => 'error']);
    }
}
