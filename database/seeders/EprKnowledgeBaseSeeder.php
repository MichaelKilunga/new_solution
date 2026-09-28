<?php

namespace Database\Seeders;

use App\Models\EprKnowledgeBase;
use Illuminate\Database\Seeder;

class EprKnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            // 1. Cap Seals Regulation 2022
            [
                'title' => 'Environmental Management Plastic Bottle Cap Seals Regulations 2022',
                'category' => 'REGULATION',
                'keywords' => 'cap, seal, caps, mifuniko, vifuniko, kanuni, 2022, plastic bottle, neck rings, banned, marufuku',
                'language' => 'sw',
                'content' => 'Kulingana na Kanuni za Usimamizi wa Mazingira (Umarufuku wa Mfuko wa Plastiki na Vifuniko vya Plastiki vya Ziada) za mwaka 2022, matumizi ya vifuniko vya plastiki vyenye nembo ya ziada (cap seals/shrink sleeves) kwenye chupa yamepigwa marufuku nchini Tanzania. Wazalishaji wanatakiwa kutumia mifuniko iliyounganishwa au neck rings zilizoidhinishwa na TBS na NEMC ili kupunguza taka na kuongeza urejelezaji.',
            ],
            [
                'title' => 'Plastic Bottle Cap Seals Prohibition Guidelines 2022',
                'category' => 'REGULATION',
                'keywords' => 'cap, seal, caps, regulation, 2022, plastic bottle, neck rings, banned, NEMC, TBS',
                'language' => 'en',
                'content' => 'Under the Environmental Management (Prohibition of Plastic Carrier Bags and Plastic Bottle Cap Seals) Regulations 2022, secondary shrink sleeves and cap seals on beverage bottles are strictly prohibited in Tanzania. Producers must use tethered caps or approved neck rings to streamline PET recycling and avoid compliance penalties.',
            ],

            // 2. Eco-Modulated Fee Tiers
            [
                'title' => 'Viwango vya Ada za Uzalishaji (EPR Eco-Modulated Fees)',
                'category' => 'FEE_TIERS',
                'keywords' => 'fee, ada, tiers, tarifu, packaging, producer, eco-fee, mzalishaji, plastiki, PET, HDPE, multilayer',
                'language' => 'sw',
                'content' => 'Mfumo wa EPR Tanzania unatumia ada za eco-modulation katika ngazi 3: Tier 1 (Chupa safi za PET zisizo na rangi): TZS 15,000 kwa tani. Tier 2 (PET yenye rangi au HDPE): TZS 25,000 kwa tani. Tier 3 (Plastiki ya safu nyingi / Multilayer isiyorejelezeka kwa urahisi): TZS 50,000 kwa tani. Kubadili mifuniko kuwa isiyo na rangi kupunguza ada hadi Tier 1.',
            ],
            [
                'title' => 'EPR Eco-Modulated Fee Tariffs in Tanzania',
                'category' => 'FEE_TIERS',
                'keywords' => 'fee, tiers, producer fee, eco-modulation, tariff, PET, HDPE, multilayer, tonnage',
                'language' => 'en',
                'content' => 'Tanzania EPR applies eco-modulated fee tiers based on recyclability: Tier 1 (Clear, uncolored PET with compatible caps): TZS 15,000 per tonne. Tier 2 (Colored PET or rigid HDPE): TZS 25,000 per tonne. Tier 3 (Multilayer / non-recyclable flexible plastics): TZS 50,000 per tonne.',
            ],

            // 3. Eco-Design Standards
            [
                'title' => 'Miongozo ya Ubunifu Rafiki wa Mazingira (Eco-Design)',
                'category' => 'ECO_DESIGN',
                'keywords' => 'design, ubunifu, eco-design, label, gundi, adhesive, mono-material, chupa, rangi, clear PET',
                'language' => 'sw',
                'content' => 'Miongozo ya NEMC ya Eco-Design inasisitiza: 1. Tumia plastiki ya mono-material badala ya kuchanganya plastic aina mbalimbali. 2. Tumia chupa safi zisizo na rangi (clear PET) kwa urahisi wa urejelezaji. 3. Epuka gundi inayoshika sana kwenye lebo inayozuia kutenganisha plastiki wakati wa kuosha.',
            ],
            [
                'title' => 'Eco-Design Packaging Guidelines for Producers',
                'category' => 'ECO_DESIGN',
                'keywords' => 'eco-design, design, mono-material, clear PET, water soluble glue, packaging, recyclability',
                'language' => 'en',
                'content' => 'NEMC Eco-Design Guidelines for beverage and consumer packaging: 1. Adopt mono-material design. 2. Transition from opaque/colored PET to transparent clear PET. 3. Use water-soluble label adhesives to facilitate high-grade wash and recycling.',
            ],

            // 4. Recycling & Sorting Guidelines for Waste Pickers
            [
                'title' => 'Mwongozo wa Kutenganisha Taka za Plastiki kwa Watoza Taka',
                'category' => 'RECYCLING_GUIDE',
                'keywords' => 'recycling, sorting, watoza taka, scrap, bei, PET, HDPE, chupa, maandalizi, buyback',
                'language' => 'sw',
                'content' => 'Mambo muhimu ya kupata bei ya juu ya plastiki: 1. Tenganisha chupa safi za PET (chupa za maji na vinywaji) na chupa za rangi. 2. Odoa mifuniko yenye rangi na lebo ili kupata daraja la kwanza (Grade A). 3. Hifadhi plastiki mahali pakavu kabla ya kupeleka kituoni. Chupa safi za PET hununuliwa hadi TZS 420/kg.',
            ],
            [
                'title' => 'Waste Collector Plastic Sorting & Value Optimization Guide',
                'category' => 'RECYCLING_GUIDE',
                'keywords' => 'sorting, waste picker, collector, grade A, PET, HDPE, buyback price, clean bottles',
                'language' => 'en',
                'content' => 'Value optimization guide for informal waste pickers: 1. Separate clear PET from colored plastics. 2. Remove caps and rings to qualify for Grade A pricing (up to TZS 420/kg). 3. Compress clean bottles into bales before transport to aggregators.',
            ],

            // 5. Producer Responsibility Organizations (PROs) & Compliance Rules
            [
                'title' => 'Usajili na Wajibu wa Wazalishaji katika PRO (Producer Responsibility Organization)',
                'category' => 'REGULATION',
                'keywords' => 'PRO, registration, wazalishaji, wajibu, NEMC, usajili, ada, ripoti, ripoti ya robo mwaka',
                'language' => 'sw',
                'content' => 'Kila mzalishaji au mwagizaji wa bidhaa za plastiki anapaswa kusajiliwa na NEMC na kujiunga na Producer Responsibility Organization (PRO) iliyoidhinishwa. Kazi ya PRO ni kuratibu ukusanyaji wa plastiki, kufadhili vituo vya urejelezaji, na kuwasilisha ripoti za kufuata sheria kila robo mwaka.',
            ],
        ];

        foreach ($articles as $article) {
            EprKnowledgeBase::create($article);
        }
    }
}
