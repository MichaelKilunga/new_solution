<!DOCTYPE html>
<html lang="sw" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | EcoHuru EPR Platform</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .card-hover {
            transition: all 0.2s ease-in-out;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50">
    <div class="min-h-full flex flex-col">
        
        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col space-y-3 pointer-events-none max-w-md w-full px-4"></div>

        <!-- Solid Plain White Header Bar -->
        <header class="bg-white text-slate-900 shadow-sm border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700 text-2xl shadow-sm">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-black tracking-tight text-slate-900">EcoHuru Admin Portal</h1>
                                <span class="bg-emerald-50 text-emerald-800 text-xs px-3 py-0.5 rounded-full border border-emerald-200 font-bold tracking-wide flex items-center gap-1.5">
                                    <i class="bi bi-shield-check text-emerald-600"></i> Protected Admin Area
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">System Telemetry & Compliance Control | Logged in as <strong class="text-slate-900 font-bold">{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 text-xs">
                        <a href="{{ route('public.portal') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3.5 py-2 rounded-xl border border-slate-200 transition flex items-center gap-1.5">
                            <i class="bi bi-globe text-slate-500"></i> Public Portal
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold px-3.5 py-2 rounded-xl border border-rose-200 transition flex items-center gap-1.5">
                                <i class="bi bi-box-arrow-right"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <!-- Real-Time Metrics Header Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Card 1: Total Guidance Queries -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm card-hover flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Inquiries Processed</span>
                        <div class="text-3xl font-black text-slate-900 mt-1" id="stat-inquiries">{{ $metrics['total_inquiries'] }}</div>
                        <div class="text-xs text-slate-500 mt-1.5 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/60">
                                <i class="bi bi-phone"></i> {{ $metrics['total_sms_inquiries'] }} SMS
                            </span>
                            <span class="inline-flex items-center gap-1 font-semibold text-slate-600">
                                <i class="bi bi-globe"></i> {{ $metrics['total_web_inquiries'] }} Web
                            </span>
                        </div>
                    </div>
                    <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                </div>

                <!-- Card 2: Community Leakage Alerts -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm card-hover flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Community Leakage Alerts</span>
                        <div class="text-3xl font-black text-amber-600 mt-1" id="stat-reports">{{ $metrics['total_leakage_reports'] }}</div>
                        <div class="text-xs text-slate-500 mt-1.5 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60">
                                <i class="bi bi-clock-history"></i> {{ $metrics['pending_leakage_reports'] }} Pending Dispatch
                            </span>
                        </div>
                    </div>
                    <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shadow-sm border border-amber-100">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>

                <!-- Card 3: Declared Eco-Fees -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm card-hover flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Declared Eco-Fees</span>
                        <div class="text-2xl font-black text-emerald-800 mt-1" id="stat-fees">TZS {{ number_format($metrics['total_eco_fees_tzs']) }}</div>
                        <div class="text-xs text-slate-500 mt-1.5">
                            <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                <i class="bi bi-box-seam"></i> {{ number_format($metrics['total_declared_tonnage'], 1) }} Tons Plastic
                            </span>
                        </div>
                    </div>
                    <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-sm border border-emerald-100">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>

                <!-- Card 4: Verified Recyclers -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm card-hover flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Verified Recycling Hubs</span>
                        <div class="text-3xl font-black text-slate-900 mt-1">{{ $metrics['verified_recyclers_count'] }}</div>
                        <div class="text-xs text-slate-500 mt-1.5 flex items-center gap-1 font-medium text-slate-600">
                            <i class="bi bi-geo-alt-fill text-emerald-600"></i> Dar es Salaam, Arusha, Mwanza
                        </div>
                    </div>
                    <div class="w-13 h-13 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shadow-sm border border-indigo-100">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Tabbed Interface Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <!-- Tab Header Bar -->
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 flex flex-wrap gap-4 justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="bi bi-sliders text-emerald-700 text-lg"></i>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight">System Operations & Compliance Desk</h2>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="flex flex-wrap gap-1.5 bg-slate-200/60 p-1.5 rounded-xl border border-slate-200">
                        <button onclick="switchTab('tab-simulator')" id="btn-tab-simulator" class="px-4 py-2 text-xs font-bold rounded-lg transition bg-white text-slate-900 shadow-sm flex items-center gap-2 border border-slate-200">
                            <i class="bi bi-phone-vibrate text-blue-600"></i> Dual Channel Simulator
                        </button>
                        <button onclick="switchTab('tab-producer')" id="btn-tab-producer" class="px-4 py-2 text-xs font-bold rounded-lg transition text-slate-600 hover:text-slate-900 flex items-center gap-2">
                            <i class="bi bi-calculator-fill text-emerald-600"></i> Producer Compliance
                        </button>
                        <button onclick="switchTab('tab-reports')" id="btn-tab-reports" class="px-4 py-2 text-xs font-bold rounded-lg transition text-slate-600 hover:text-slate-900 flex items-center gap-2">
                            <i class="bi bi-megaphone-fill text-amber-600"></i> Waste Leakage Alerts
                        </button>
                        <button onclick="switchTab('tab-directory')" id="btn-tab-directory" class="px-4 py-2 text-xs font-bold rounded-lg transition text-slate-600 hover:text-slate-900 flex items-center gap-2">
                            <i class="bi bi-building-check text-indigo-600"></i> Recycler Directory
                        </button>
                    </div>
                </div>

                <!-- Tab Content Body -->
                <div class="p-6">

                    <!-- ================= TAB 1: DUAL SIMULATOR ================= -->
                    <div id="tab-simulator" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <!-- Feature Phone SMS Terminal -->
                            <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                                                <i class="bi bi-phone text-emerald-600"></i> Feature Phone SMS Simulator
                                            </h3>
                                        </div>
                                        <span class="text-[11px] bg-emerald-50 text-emerald-800 font-mono font-bold px-2.5 py-1 rounded-md border border-emerald-200">Shortcode: 15054</span>
                                    </div>

                                    <p class="text-xs text-slate-500 mt-3 mb-3">Simulate offline feature phone users executing Africa's Talking SMS protocols:</p>

                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <button onclick="setSmsInput('EPR RECYCLER Temeke PET')" class="text-[11px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 font-medium transition flex items-center gap-1.5">
                                            <i class="bi bi-search text-blue-600"></i> EPR RECYCLER Temeke PET
                                        </button>
                                        <button onclick="setSmsInput('EPR FEE PET clear 10 tons')" class="text-[11px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 font-medium transition flex items-center gap-1.5">
                                            <i class="bi bi-calculator text-emerald-600"></i> EPR FEE PET clear 10 tons
                                        </button>
                                        <button onclick="setSmsInput('EPR REGULATION bottle cap seals')" class="text-[11px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 font-medium transition flex items-center gap-1.5">
                                            <i class="bi bi-shield-check text-amber-600"></i> EPR REGULATION bottle cap seals
                                        </button>
                                        <button onclick="setSmsInput('EPR RIPOTI Ilala Daraja la Msimbazi')" class="text-[11px] bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 font-medium transition flex items-center gap-1.5">
                                            <i class="bi bi-exclamation-diamond text-purple-600"></i> EPR RIPOTI Ilala Msimbazi
                                        </button>
                                    </div>

                                    <div id="sms-screen" class="bg-slate-50 rounded-xl p-4 font-mono text-xs text-slate-800 min-h-[170px] max-h-[230px] overflow-y-auto border border-slate-200 space-y-2">
                                        <div class="text-slate-400">// SMS Gateway Ready (Keyword: EPR). Enter command below...</div>
                                    </div>
                                </div>

                                <form onsubmit="sendSimulatedSms(event)" class="flex gap-2 pt-2">
                                    <input type="text" id="sms-input" placeholder="e.g. EPR RECYCLER Temeke PET" class="flex-1 bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-600 font-mono">
                                    <button type="submit" id="btn-send-sms" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow flex items-center gap-2">
                                        <i class="bi bi-send-fill"></i> Send SMS
                                    </button>
                                </form>
                            </div>

                            <!-- Web Knowledge Guidance Desk -->
                            <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                                                <i class="bi bi-book-half"></i>
                                            </div>
                                            <h3 class="font-bold text-slate-900 text-sm">EPR Knowledge & Regulatory Guidance Desk</h3>
                                        </div>
                                        <span class="text-[11px] bg-blue-50 text-blue-800 font-bold px-2.5 py-1 rounded-full border border-blue-200">Swahili & English Desk</span>
                                    </div>

                                    <p class="text-xs text-slate-500 mt-3 mb-3">Ask plain-text questions regarding Tanzanian plastic regulations and compliance:</p>

                                    <div id="web-chat-box" class="bg-slate-50 rounded-xl p-4 text-xs text-slate-800 min-h-[170px] max-h-[230px] overflow-y-auto border border-slate-200 space-y-3">
                                        <div class="bg-blue-50 p-3.5 rounded-xl border border-blue-100 text-blue-950 space-y-1">
                                            <div class="font-bold text-xs flex items-center gap-1.5 text-blue-900">
                                                <i class="bi bi-info-circle-fill text-blue-600"></i> EcoHuru Guidance Desk:
                                            </div>
                                            <p class="text-slate-700 leading-relaxed">Hujambo! Uliza swali lolote kuhusu sheria za plastiki (Kanuni za Mazingira 2022), ada za eco-modulation za wazalishaji, au vituo vya urejelezaji nchini Tanzania.</p>
                                        </div>
                                    </div>
                                </div>

                                <form onsubmit="sendWebChat(event)" class="flex gap-2 pt-2">
                                    <input type="text" id="web-chat-input" placeholder="e.g. Je sheria inasemaje kuhusu mifuniko ya chupa za maji?" class="flex-1 bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600">
                                    <button type="submit" id="btn-send-web" class="bg-blue-700 hover:bg-blue-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow flex items-center gap-2">
                                        <i class="bi bi-chat-text-fill"></i> Ask Question
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- ================= TAB 2: PRODUCER COMPLIANCE ================= -->
                    <div id="tab-producer" class="hidden space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            <!-- Producer Declaration Form -->
                            <div class="lg:col-span-1 bg-slate-50 border border-slate-200 p-6 rounded-2xl space-y-4">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                                        <i class="bi bi-calculator-fill text-emerald-600"></i> Producer Eco-Fee Calculator
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1">Calculate quarterly fees according to NEMC eco-design tariffs.</p>
                                </div>

                                <form onsubmit="submitDeclaration(event)" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-building text-slate-400"></i> Company / Brand Name
                                        </label>
                                        <input type="text" id="dec-company" required placeholder="e.g. Serengeti Bottlers Ltd" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-card-text text-slate-400"></i> TIN Number
                                        </label>
                                        <input type="text" id="dec-tin" required placeholder="e.g. 104-555-789" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-box-seam text-slate-400"></i> Packaging Description
                                        </label>
                                        <input type="text" id="dec-packaging" required placeholder="e.g. 500ml Clear PET Water Bottle" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-layers text-slate-400"></i> Material Eco-Category
                                        </label>
                                        <select id="dec-category" onchange="updateLiveFeeCalculation()" required class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600">
                                            <option value="Tier 1 (Clear PET)">Tier 1: Clear Uncolored PET (TZS 15,000 / ton)</option>
                                            <option value="Tier 2 (Colored PET/HDPE)">Tier 2: Colored PET / Rigid HDPE (TZS 25,000 / ton)</option>
                                            <option value="Tier 3 (Multilayer)">Tier 3: Multilayer / Non-Recyclable (TZS 50,000 / ton)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-speedometer2 text-slate-400"></i> Quarterly Tonnage (Tons)
                                        </label>
                                        <input type="number" step="0.1" id="dec-tonnage" oninput="updateLiveFeeCalculation()" required placeholder="e.g. 20.0" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600">
                                    </div>

                                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 text-xs text-emerald-950 space-y-1">
                                        <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Estimated Eco-Fee Preview:</div>
                                        <div class="text-xl font-black text-emerald-800" id="live-fee-preview">TZS 0</div>
                                        <p class="text-[11px] text-emerald-700" id="live-fee-advice">Enter tonnage above to preview fee</p>
                                    </div>

                                    <button type="submit" id="btn-submit-dec" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                                        <i class="bi bi-file-earmark-check-fill"></i> Submit Formal Declaration
                                    </button>
                                </form>
                            </div>

                            <!-- Declarations Table -->
                            <div class="lg:col-span-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                                        <i class="bi bi-journal-text text-slate-700"></i> Registered Producer Declarations Log
                                    </h3>
                                    <span class="text-xs text-slate-500 font-medium">NEMC Telemetry Sync Active</span>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-2xl overflow-x-auto shadow-sm">
                                    <table class="w-full text-left text-xs text-slate-700">
                                        <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                                            <tr>
                                                <th class="p-3.5">Company & TIN</th>
                                                <th class="p-3.5">Packaging Details</th>
                                                <th class="p-3.5">Quarterly Tonnage</th>
                                                <th class="p-3.5">Calculated Fee</th>
                                                <th class="p-3.5">Compliance Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100" id="declarations-body">
                                            @foreach($recentDeclarations as $dec)
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="p-3.5 font-bold text-slate-900">
                                                    {{ $dec->company_name }}
                                                    <div class="text-[10px] font-normal text-slate-400 font-mono mt-0.5">TIN: {{ $dec->tin_number }}</div>
                                                </td>
                                                <td class="p-3.5">
                                                    <div class="font-semibold text-slate-800">{{ $dec->packaging_type }}</div>
                                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                        {{ $dec->material_category }}
                                                    </span>
                                                </td>
                                                <td class="p-3.5 font-mono font-bold text-slate-800">{{ number_format($dec->quarterly_tonnage, 1) }} tons</td>
                                                <td class="p-3.5 font-black text-emerald-800 text-sm">TZS {{ number_format($dec->calculated_eco_fee) }}</td>
                                                <td class="p-3.5">
                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200 flex items-center gap-1 w-max">
                                                        <i class="bi bi-check-circle-fill text-blue-600"></i> {{ $dec->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ================= TAB 3: COMMUNITY LEAKAGE ALERTS ================= -->
                    <div id="tab-reports" class="hidden space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            <!-- Leakage Alert Form -->
                            <div class="lg:col-span-1 bg-amber-50/50 border border-amber-200 p-6 rounded-2xl space-y-4">
                                <div>
                                    <h3 class="font-bold text-amber-950 text-base flex items-center gap-2">
                                        <i class="bi bi-megaphone-fill text-amber-600"></i> Citizen Waste Alert Dispatch
                                    </h3>
                                    <p class="text-xs text-slate-600 mt-1">Report clogged drainage or severe plastic waste accumulation hotspots.</p>
                                </div>

                                <form onsubmit="submitLeakageReport(event)" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-telephone text-slate-400"></i> Reporter Phone Number
                                        </label>
                                        <input type="text" id="rep-phone" required placeholder="e.g. 0712-345-678" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-geo-alt text-slate-400"></i> Target District
                                        </label>
                                        <select id="rep-district" required class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                                            <option value="Ilala">Ilala (Dar es Salaam)</option>
                                            <option value="Temeke">Temeke (Dar es Salaam)</option>
                                            <option value="Kinondoni">Kinondoni (Dar es Salaam)</option>
                                            <option value="Dodoma">Dodoma City</option>
                                            <option value="Arusha">Arusha City</option>
                                            <option value="Mwanza">Nyamagana (Mwanza)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-pin-map text-slate-400"></i> Specific Location / Landmark
                                        </label>
                                        <input type="text" id="rep-location" required placeholder="e.g. Daraja la Msimbazi, Mkazinga Ward" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                                            <i class="bi bi-text-left text-slate-400"></i> Incident Description
                                        </label>
                                        <textarea id="rep-description" required rows="3" placeholder="e.g. Mfereji umefungwa na chupa za maji na taka za plastiki zilizorundikana" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500"></textarea>
                                    </div>

                                    <button type="submit" id="btn-submit-report" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                                        <i class="bi bi-send-exclamation-fill"></i> Dispatch Alert to Officer
                                    </button>
                                </form>
                            </div>

                            <!-- Leakage Hotline Table -->
                            <div class="lg:col-span-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                                        <i class="bi bi-exclamation-square-fill text-amber-600"></i> Live Community Leakage Incident Log
                                    </h3>
                                    <span class="text-xs text-slate-500 font-medium">Municipal Officers Notified</span>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-2xl overflow-x-auto shadow-sm">
                                    <table class="w-full text-left text-xs text-slate-700">
                                        <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                                            <tr>
                                                <th class="p-3.5">Ref Code</th>
                                                <th class="p-3.5">District & Location</th>
                                                <th class="p-3.5">Description</th>
                                                <th class="p-3.5">Assigned Officer</th>
                                                <th class="p-3.5">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100" id="reports-body">
                                            @foreach($recentReports as $rep)
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="p-3.5 font-mono font-bold text-amber-700 text-xs">{{ $rep->report_code }}</td>
                                                <td class="p-3.5 font-bold text-slate-900">
                                                    {{ $rep->district }}
                                                    <div class="text-[10px] font-normal text-slate-400 mt-0.5"><i class="bi bi-geo-alt"></i> {{ $rep->location_details }}</div>
                                                </td>
                                                <td class="p-3.5 text-slate-600 max-w-[220px] truncate">{{ $rep->description }}</td>
                                                <td class="p-3.5 text-slate-800 font-medium">{{ $rep->assigned_to ?? 'Municipal Environmental Officer' }}</td>
                                                <td class="p-3.5">
                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200 flex items-center gap-1 w-max">
                                                        <i class="bi bi-hourglass-split text-amber-700"></i> {{ $rep->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ================= TAB 4: RECYCLERS DIRECTORY ================= -->
                    <div id="tab-directory" class="hidden space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                                    <i class="bi bi-building-check text-indigo-600"></i> Certified Recycling Hubs & Aggregators Directory
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Verified local aggregators offering live plastic buyback rates (TZS/kg).</p>
                            </div>

                            <div class="relative min-w-[240px]">
                                <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs"></i>
                                <input type="text" id="recycler-search" onkeyup="filterRecyclers()" placeholder="Filter by district or material..." class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 text-xs focus:outline-none focus:border-indigo-600">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="recyclers-grid">
                            @foreach($recyclers as $hub)
                            <div class="recycler-card bg-white border border-slate-200 p-5 rounded-2xl shadow-sm card-hover flex flex-col justify-between" data-search="{{ strtolower($hub->name . ' ' . $hub->district . ' ' . $hub->region . ' ' . $hub->accepted_materials) }}">
                                <div>
                                    <div class="flex items-start justify-between">
                                        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $hub->provider_type }}
                                        </span>
                                        <span class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold shadow-sm" title="Verified Provider">
                                            <i class="bi bi-check-lg"></i>
                                        </span>
                                    </div>
                                    <h4 class="font-extrabold text-slate-900 text-sm mt-3">{{ $hub->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <i class="bi bi-geo-alt-fill text-slate-400"></i> {{ $hub->district }}, {{ $hub->region }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-600 flex items-center gap-1">
                                            <i class="bi bi-telephone-fill text-slate-400"></i> {{ $hub->phone }}
                                        </span>
                                        <span class="font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded text-[11px] border border-emerald-200">
                                            {{ $hub->accepted_materials }}
                                        </span>
                                    </div>

                                    @if($hub->buyback_rates_json)
                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 text-[11px] font-mono text-slate-700 flex items-center gap-2">
                                        <i class="bi bi-tag-fill text-indigo-500"></i>
                                        <span>Buyback: <strong>{{ $hub->buyback_rates_json }}</strong></span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <!-- Solid Plain White Footer -->
        <footer class="bg-white text-slate-600 text-xs py-6 border-t border-slate-200 mt-auto shadow-inner">
            <div class="max-w-7xl mx-auto px-4 text-center space-y-2">
                <p>EcoHuru Extended Producer Responsibility (EPR) Platform &copy; 2026. Aligned with Tanzania 2025–2030 National Waste Management Strategy.</p>
                <p class="text-slate-500">Shortcode: <strong class="text-emerald-700 font-mono">15054</strong> | Keyword: <strong class="text-emerald-700 font-mono">EPR</strong> | National Environmental Management Council (NEMC)</p>
            </div>
        </footer>
    </div>

    <!-- Interactive JavaScript Engine -->
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const isSuccess = type === 'success';
            const bgClass = isSuccess ? 'bg-white text-slate-900 border-emerald-600' : 'bg-white text-slate-900 border-rose-600';
            const iconClass = isSuccess ? 'bi-check-circle-fill text-emerald-600' : 'bi-exclamation-octagon-fill text-rose-600';

            toast.className = `p-4 rounded-xl shadow-xl border-l-4 ${bgClass} border-y border-r border-slate-200 transition-all duration-300 transform translate-y-2 opacity-0 flex items-start gap-3 pointer-events-auto`;
            toast.innerHTML = `
                <i class="bi ${iconClass} text-lg mt-0.5"></i>
                <div class="flex-1 text-xs leading-relaxed font-semibold">${message}</div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700"><i class="bi bi-x text-lg"></i></button>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        function switchTab(tabId) {
            ['tab-simulator', 'tab-producer', 'tab-reports', 'tab-directory'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            document.getElementById(tabId).classList.remove('hidden');

            const buttons = ['btn-tab-simulator', 'btn-tab-producer', 'btn-tab-reports', 'btn-tab-directory'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                btn.className = "px-4 py-2 text-xs font-bold rounded-lg transition text-slate-600 hover:text-slate-900 flex items-center gap-2";
            });

            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.className = "px-4 py-2 text-xs font-bold rounded-lg transition bg-white text-slate-900 shadow-sm flex items-center gap-2 border border-slate-200";
        }

        function setSmsInput(cmd) {
            document.getElementById('sms-input').value = cmd;
        }

        async function sendSimulatedSms(e) {
            e.preventDefault();
            const input = document.getElementById('sms-input');
            const text = input.value.trim();
            if (!text) return;

            const screen = document.getElementById('sms-screen');
            screen.innerHTML += `<div class="text-slate-900 font-bold mt-2">&gt; INBOUND SMS: ${text}</div>`;
            screen.scrollTop = screen.scrollHeight;
            input.value = '';

            try {
                const res = await fetch('/api/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ message: text, phone: '0712-345-678' })
                });
                const data = await res.json();
                screen.innerHTML += `<div class="text-emerald-700 font-semibold mt-1">&lt; SMS DISPATCH: ${data.response}</div>`;
                screen.scrollTop = screen.scrollHeight;
                showToast('SMS query processed via Gateway 15054');
            } catch (err) {
                screen.innerHTML += `<div class="text-rose-600 mt-1">Error processing SMS command</div>`;
                showToast('Error processing SMS command', 'error');
            }
        }

        async function sendWebChat(e) {
            e.preventDefault();
            const input = document.getElementById('web-chat-input');
            const text = input.value.trim();
            if (!text) return;

            const chatBox = document.getElementById('web-chat-box');
            chatBox.innerHTML += `<div class="bg-white p-3 rounded-xl border border-slate-200 text-slate-900 font-medium"><strong>You:</strong> ${text}</div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
            input.value = '';

            try {
                const res = await fetch('/api/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ message: text })
                });
                const data = await res.json();
                chatBox.innerHTML += `
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 text-blue-950 space-y-1">
                        <div class="font-bold text-xs text-blue-900"><i class="bi bi-info-circle-fill text-blue-600"></i> Guidance Desk:</div>
                        <p>${data.response}</p>
                    </div>`;
                chatBox.scrollTop = chatBox.scrollHeight;
            } catch (err) {
                chatBox.innerHTML += `<div class="bg-rose-50 p-3 rounded-xl text-rose-700 border border-rose-100">Error connecting to guidance service.</div>`;
                showToast('Error fetching regulatory response', 'error');
            }
        }

        function updateLiveFeeCalculation() {
            const tonnage = parseFloat(document.getElementById('dec-tonnage').value) || 0;
            const category = document.getElementById('dec-category').value;
            
            let rate = 25000;
            if (category.includes('Tier 1')) rate = 15000;
            if (category.includes('Tier 3')) rate = 50000;

            const fee = tonnage * rate;
            document.getElementById('live-fee-preview').innerText = 'TZS ' + new Intl.NumberFormat().format(fee);
            document.getElementById('live-fee-advice').innerText = `${tonnage} tons @ TZS ${new Intl.NumberFormat().format(rate)}/ton`;
        }

        async function submitDeclaration(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-dec');
            btn.disabled = true;

            const payload = {
                company_name: document.getElementById('dec-company').value,
                tin_number: document.getElementById('dec-tin').value,
                packaging_type: document.getElementById('dec-packaging').value,
                material_category: document.getElementById('dec-category').value,
                quarterly_tonnage: parseFloat(document.getElementById('dec-tonnage').value),
            };

            try {
                const res = await fetch('/api/producer/declaration', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Declaration Recorded! Fee: TZS ' + new Intl.NumberFormat().format(data.declaration.calculated_eco_fee));
                    setTimeout(() => location.reload(), 1200);
                }
            } catch (err) {
                showToast('Failed to submit declaration', 'error');
                btn.disabled = false;
            }
        }

        async function submitLeakageReport(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-report');
            btn.disabled = true;

            const payload = {
                sender_phone: document.getElementById('rep-phone').value,
                district: document.getElementById('rep-district').value,
                location_details: document.getElementById('rep-location').value,
                description: document.getElementById('rep-description').value,
            };

            try {
                const res = await fetch('/api/leakage-report', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Waste Leakage Alert Dispatched! Ref: ' + data.report.report_code);
                    setTimeout(() => location.reload(), 1200);
                }
            } catch (err) {
                showToast('Failed to dispatch alert', 'error');
                btn.disabled = false;
            }
        }

        function filterRecyclers() {
            const query = document.getElementById('recycler-search').value.toLowerCase();
            const cards = document.querySelectorAll('.recycler-card');

            cards.forEach(card => {
                const text = card.getAttribute('data-search');
                if (text.includes(query)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
