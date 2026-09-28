<?php

namespace Database\Seeders;

use App\Models\FieldProvider;
use Illuminate\Database\Seeder;

class FieldProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            // Temeke
            [
                'name' => 'Kijichi Sorting & Aggregation Hub',
                'provider_type' => 'AGGREGATOR_HUB',
                'region' => 'Dar es Salaam',
                'district' => 'Temeke',
                'ward' => 'Kijichi',
                'phone' => '0712-345-678',
                'accepted_materials' => 'PET, HDPE, LDPE',
                'buyback_rates_json' => json_encode(['PET' => 400, 'HDPE' => 350, 'LDPE' => 250]),
                'is_verified' => true,
            ],
            [
                'name' => "Chang'ombe Green Recyclers Ltd",
                'provider_type' => 'RECYCLER',
                'region' => 'Dar es Salaam',
                'district' => 'Temeke',
                'ward' => "Chang'ombe",
                'phone' => '0754-123-456',
                'accepted_materials' => 'PET, PP, HDPE',
                'buyback_rates_json' => json_encode(['PET' => 420, 'HDPE' => 380, 'PP' => 300]),
                'is_verified' => true,
            ],

            // Ilala
            [
                'name' => 'Ilala Eco Recovery Center',
                'provider_type' => 'AGGREGATOR_HUB',
                'region' => 'Dar es Salaam',
                'district' => 'Ilala',
                'ward' => 'Vingunguti',
                'phone' => '0788-901-234',
                'accepted_materials' => 'PET, MULTILAYER, HDPE',
                'buyback_rates_json' => json_encode(['PET' => 410, 'HDPE' => 360, 'MULTILAYER' => 150]),
                'is_verified' => true,
            ],
            [
                'name' => 'NEMC Coast & Dar Inspection Office',
                'provider_type' => 'INSPECTION_OFFICE',
                'region' => 'Dar es Salaam',
                'district' => 'Ilala',
                'ward' => 'City Center',
                'phone' => '022-211-1234',
                'accepted_materials' => 'N/A (Regulatory Inspection)',
                'buyback_rates_json' => null,
                'is_verified' => true,
            ],

            // Kinondoni
            [
                'name' => 'Kinondoni Waste Collectors Cooperative',
                'provider_type' => 'AGGREGATOR_HUB',
                'region' => 'Dar es Salaam',
                'district' => 'Kinondoni',
                'ward' => 'Mwenge',
                'phone' => '0655-789-012',
                'accepted_materials' => 'PET, HDPE, GLASS',
                'buyback_rates_json' => json_encode(['PET' => 390, 'HDPE' => 340]),
                'is_verified' => true,
            ],
            [
                'name' => 'Tanzania Packaging PRO Alliance (TPA)',
                'provider_type' => 'PRO',
                'region' => 'Dar es Salaam',
                'district' => 'Kinondoni',
                'ward' => 'Mikocheni',
                'phone' => '0713-999-888',
                'accepted_materials' => 'ALL_PLASTICS',
                'buyback_rates_json' => json_encode(['PRO_COMPLIANCE' => 'Fee Registration & Certificate']),
                'is_verified' => true,
            ],

            // Arusha
            [
                'name' => 'Arusha Clean Environment Aggregators',
                'provider_type' => 'AGGREGATOR_HUB',
                'region' => 'Arusha',
                'district' => 'Arusha City',
                'ward' => 'Unga Ltd',
                'phone' => '0767-444-555',
                'accepted_materials' => 'PET, HDPE',
                'buyback_rates_json' => json_encode(['PET' => 380, 'HDPE' => 330]),
                'is_verified' => true,
            ],

            // Mwanza
            [
                'name' => 'Nyamagana Lake Recyclers',
                'provider_type' => 'RECYCLER',
                'region' => 'Mwanza',
                'district' => 'Nyamagana',
                'ward' => 'Mabatini',
                'phone' => '0742-111-222',
                'accepted_materials' => 'PET, HDPE, PP',
                'buyback_rates_json' => json_encode(['PET' => 400, 'HDPE' => 350]),
                'is_verified' => true,
            ],
        ];

        foreach ($providers as $provider) {
            FieldProvider::create($provider);
        }
    }
}
