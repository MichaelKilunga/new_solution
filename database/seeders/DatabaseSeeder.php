<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed core system settings
        SystemSetting::set('ai_model', 'gemini-flash-lite-latest');
        SystemSetting::set('ai_max_words', '160');
        SystemSetting::set('sms_shortcode', '15054');
        SystemSetting::set('sms_keyword', 'EPR');
        SystemSetting::set('default_language', 'sw');

        // Seed domain datasets
        $this->call([
            EprKnowledgeBaseSeeder::class,
            FieldProviderSeeder::class,
        ]);

        // Create or update default admin user for NEMC / PRO web portal
        User::updateOrCreate(
            ['email' => 'admin@nemc.go.tz'],
            [
                'name' => 'NEMC Compliance Admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
