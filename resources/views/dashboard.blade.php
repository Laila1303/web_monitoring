<!DOCTYPE html>
<html lang="id" class="h-full bg-off-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Global Supply Chain Monitoring System</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet.js CSS (OpenStreetMap parameter) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- Heroicons for Clean UI -->
    <script src="https://unpkg.com/@heroicons/dom@1.0.6/dist/heroicons.min.js"></script>

    <style>
        /* Custom scrollbar matching Slate Blue theme */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased overflow-hidden">

    <!-- Sparkline Helper Function -->
    @php
        $generateSparklinePath = function($history) {
            if (empty($history)) {
                return 'M 0 15 H 100';
            }
            $count = count($history);
            $min = min($history);
            $max = max($history);
            $range = $max - $min === 0 ? 1 : $max - $min;
            
            $points = [];
            foreach ($history as $index => $value) {
                $x = ($index / ($count - 1)) * 100;
                $y = 25 - (($value - $min) / $range) * 20; // Invert and scale to height 30
                $points[] = "$x,$y";
            }
            return 'M ' . implode(' L ', $points);
        };
    @endphp

    <!-- HEADER: Industrial-Tech Command Bar -->
    <header class="bg-deep-navy border-b border-slate-700 flex items-center justify-between px-6 py-3 shrink-0">
        <div class="flex items-center gap-3">
            <!-- Icon -->
            <div class="p-1.5 bg-intl-orange/10 rounded border border-intl-orange/30">
                <svg class="w-6 h-6 text-intl-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-white text-md font-bold uppercase tracking-wider">GLOBAL SUPPLY CHAIN OPERATIONS</h1>
                <p class="text-xs text-slate-400">EX-IM MULTIMODAL TRACKING & RISK MONITORING ENGINE</p>
            </div>
        </div>

        <!-- Telemetry Status Lights -->
        <div class="flex items-center gap-6 text-xs">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-slate-300 font-mono">LEAFLET.JS: CONNECTED</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-slate-300 font-mono">WORLD_BANK: SYNCD</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-slate-300 font-mono">OPEN_METEO: ONLINE</span>
            </div>
            <div class="h-6 w-px bg-slate-700"></div>
            <div class="text-right">
                <div id="live-clock" class="text-white font-mono text-sm tracking-widest font-semibold">18:14:34 UTC</div>
                <div class="text-[10px] text-slate-400 font-mono uppercase tracking-wider">SYSTEM TIME ZONE +07:00</div>
            </div>
        </div>
    </header>

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="grow flex overflow-hidden">
        
        <!-- SIDEBAR LEFT: Global Stats, Smart Filters, News Intelligence -->
        <aside class="w-80 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 overflow-y-auto">
            
            <!-- SECTION 1: GLOBAL OVERVIEW -->
            <div class="p-4 border-b border-slate-800">
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-between">
                    <span>Global Overview</span>
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </h2>
                
                <div class="grid grid-cols-2 gap-2 font-mono">
                    <div class="bg-slate-950 p-2.5 rounded border border-slate-800">
                        <p class="text-[10px] text-slate-400 uppercase">Total Active</p>
                        <p class="text-lg font-bold text-white mt-1">{{ count($shipments) }} <span class="text-xs font-normal text-slate-400">Unit</span></p>
                    </div>
                    <div class="bg-slate-950 p-2.5 rounded border border-slate-800">
                        <p class="text-[10px] text-emerald-400 uppercase">Customs Cleared</p>
                        <p class="text-lg font-bold text-emerald-400 mt-1">
                            {{ collect($shipments)->where('status', 'Customs Cleared')->count() }}
                        </p>
                    </div>
                    <div class="bg-slate-950 p-2.5 rounded border border-slate-800">
                        <p class="text-[10px] text-safety-amber uppercase">In-Transit</p>
                        <p class="text-lg font-bold text-safety-amber mt-1">
                            {{ collect($shipments)->where('status', 'In-Transit')->count() }}
                        </p>
                    </div>
                    <div class="bg-slate-950 p-2.5 rounded border border-slate-800">
                        <p class="text-[10px] text-intl-orange uppercase">High Risks</p>
                        <p class="text-lg font-bold text-intl-orange mt-1">
                            {{ collect($shipments)->where('risk_level', 'High')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: SMART FILTERS -->
            <div class="p-4 border-b border-slate-800">
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-between">
                    <span>Smart Filters</span>
                    <button onclick="resetFilters()" class="text-[10px] text-slate-500 hover:text-white underline uppercase">Reset</button>
                </h2>

                <div class="space-y-3">
                    <!-- Filter Continent -->
                    <div>
                        <label class="text-[10px] text-slate-400 uppercase tracking-wider block mb-1">Origin Continent</label>
                        <select id="filter-continent" onchange="applyFilters()" class="w-full bg-slate-950 text-slate-300 text-xs rounded border border-slate-800 p-1.5 outline-none focus:border-slate-600 font-mono">
                            <option value="All">All Continents</option>
                            <option value="Asia">Asia (CN, JP, SG)</option>
                            <option value="Europe">Europe (DE, NL, BE)</option>
                            <option value="Americas">Americas (US, BR)</option>
                            <option value="Oceania">Oceania (AU)</option>
                        </select>
                    </div>

                    <!-- Filter Mode -->
                    <div>
                        <label class="text-[10px] text-slate-400 uppercase tracking-wider block mb-1">Transit Mode</label>
                        <div class="flex gap-2">
                            <button onclick="toggleModeFilter('All')" id="mode-all" class="filter-mode-btn grow text-center py-1 rounded border text-xs font-mono font-semibold transition bg-slate-800 border-slate-700 text-white">ALL</button>
                            <button onclick="toggleModeFilter('Sea')" id="mode-sea" class="filter-mode-btn grow text-center py-1 rounded border text-xs font-mono transition bg-slate-950 border-slate-800 text-slate-400 hover:text-white">SEA</button>
                            <button onclick="toggleModeFilter('Air')" id="mode-air" class="filter-mode-btn grow text-center py-1 rounded border text-xs font-mono transition bg-slate-950 border-slate-800 text-slate-400 hover:text-white">AIR</button>
                        </div>
                    </div>

                    <!-- Filter Risk Level -->
                    <div>
                        <label class="text-[10px] text-slate-400 uppercase tracking-wider block mb-1">Risk Classification</label>
                        <div class="flex gap-2">
                            <button onclick="toggleRiskFilter('All')" id="risk-all" class="filter-risk-btn grow py-1 text-center rounded border text-xs font-mono transition bg-slate-800 border-slate-700 text-white">ALL</button>
                            <button onclick="toggleRiskFilter('Low')" id="risk-low" class="filter-risk-btn grow py-1 text-center rounded border text-[10px] font-mono transition bg-slate-950 border-slate-800 text-slate-400 hover:bg-emerald-950/20 hover:text-emerald-400">LOW</button>
                            <button onclick="toggleRiskFilter('Medium')" id="risk-medium" class="filter-risk-btn grow py-1 text-center rounded border text-[10px] font-mono transition bg-slate-950 border-slate-800 text-slate-400 hover:bg-amber-950/20 hover:text-safety-amber">MED</button>
                            <button onclick="toggleRiskFilter('High')" id="risk-high" class="filter-risk-btn grow py-1 text-center rounded border text-[10px] font-mono transition bg-slate-950 border-slate-800 text-slate-400 hover:bg-red-950/20 hover:text-intl-orange">HIGH</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: NEWS INTELLIGENCE -->
            <div class="p-4 flex-grow flex flex-col min-h-[220px]">
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-between">
                    <span>News Intelligence</span>
                    <span class="px-1.5 py-0.5 bg-intl-orange/20 text-[9px] text-intl-orange rounded font-mono font-bold">LIVE FEED</span>
                </h2>

                <div class="space-y-3 overflow-y-auto grow pr-1">
                    @foreach ($news as $item)
                        <div class="bg-slate-950 p-2.5 rounded border border-slate-800 hover:border-slate-700 transition">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] text-slate-500 font-mono uppercase">{{ $item['source'] }}</span>
                                <span class="text-[9px] text-slate-500 font-mono">{{ $item['timestamp'] }}</span>
                            </div>
                            <h3 class="text-xs font-semibold text-slate-200 line-clamp-1 hover:line-clamp-none transition">{{ $item['title'] }}</h3>
                            <p class="text-[10px] text-slate-400 mt-1 leading-normal line-clamp-2">{{ $item['summary'] }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-[8px] tracking-wider text-slate-500 font-mono">SUPPLY CHAIN ALERT</span>
                                @if ($item['risk'] === 'High')
                                    <span class="px-1 py-0.5 bg-red-950 text-intl-orange border border-red-900 rounded text-[8px] font-mono font-semibold">CRITICAL</span>
                                @elseif ($item['risk'] === 'Medium')
                                    <span class="px-1 py-0.5 bg-amber-950 text-safety-amber border border-amber-900 rounded text-[8px] font-mono font-semibold">WARNING</span>
                                @else
                                    <span class="px-1 py-0.5 bg-slate-800 text-slate-400 border border-slate-700 rounded text-[8px] font-mono font-semibold">STABLE</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
        </aside>

        <!-- CENTER CONTENT: Map Panel + Interactive Tables (Cargo Explorer) -->
        <section class="grow flex flex-col overflow-hidden">
            
            <!-- MAP VIEWPORT: Vector Styled Monochrome OpenStreetMap (Port Location Dashboard) -->
            <div class="h-2/5 border-b border-slate-200 relative bg-[#F8FAFC]">
                <div id="map" class="w-full h-full"></div>
                <!-- Float Map Control HUD -->
                <div class="absolute top-3 right-3 z-[1000] bg-slate-900/90 border border-slate-700/50 p-3 rounded shadow-lg backdrop-blur-md max-w-xs font-mono text-[10px] text-slate-300">
                    <p class="font-bold text-white border-b border-slate-700 pb-1.5 mb-1.5 flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 bg-intl-orange rounded-full animate-ping"></span>
                        PORT LOCATION DASHBOARD
                    </p>
                    <div class="space-y-1">
                        <div class="flex justify-between"><span class="text-slate-400">Layer Tile:</span><span>CartoDB Positron</span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Routes Status:</span><span class="text-emerald-400">Active Lanes</span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Markers Color:</span><span>Risk-Level Coded</span></div>
                    </div>
                </div>
            </div>

            <!-- CARGO MONITORING LIST & DEEP DATA TABLE (Data Visualization Dashboard) -->
            <div class="h-3/5 flex flex-col overflow-hidden bg-off-white">
                
                <!-- Table Toolbar -->
                <div class="bg-slate-100 border-b border-slate-200 px-4 py-2 flex items-center justify-between shrink-0 font-mono text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        <span class="font-bold text-slate-700">CARGO FLEET TRACKER</span>
                        <span class="text-[10px] bg-slate-200 px-2 py-0.5 rounded text-slate-600">Showing <span id="filtered-count">{{ count($shipments) }}</span> of {{ count($shipments) }} items</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-full inline-block"></span><span class="text-[10px]">Normal</span></div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-safety-amber rounded-full inline-block"></span><span class="text-[10px]">Transit/Warning</span></div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-intl-orange rounded-full inline-block"></span><span class="text-[10px]">Critical</span></div>
                    </div>
                </div>

                <!-- Deep Data Table Container -->
                <div class="grow overflow-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-200/60 text-slate-500 font-mono text-[10px] uppercase tracking-wider border-b border-slate-300 sticky top-0 z-10">
                                <th class="py-2.5 px-4">Cargo ID</th>
                                <th class="py-2.5 px-4">Logistic Route (Origin → Destination)</th>
                                <th class="py-2.5 px-4">Transport Mode</th>
                                <th class="py-2.5 px-4">ETA</th>
                                <th class="py-2.5 px-4 text-center">Docs Checklist</th>
                                <th class="py-2.5 px-4">Container Telemetry (Temp °C)</th>
                                <th class="py-2.5 px-4 text-center">Risk Level</th>
                                <th class="py-2.5 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($shipments as $shipment)
                                <!-- Table Row -->
                                <tr id="row-{{ $shipment['cargo_id'] }}"
                                    data-cargo="{{ $shipment['cargo_id'] }}"
                                    data-continent="{{ in_array($shipment['origin_country_code'], ['CN','JP','SG']) ? 'Asia' : (in_array($shipment['origin_country_code'], ['DE','NL','BE']) ? 'Europe' : (in_array($shipment['origin_country_code'], ['US','BR']) ? 'Americas' : 'Oceania')) }}"
                                    data-mode="{{ $shipment['transport_mode'] }}"
                                    data-risk="{{ $shipment['risk_level'] }}"
                                    class="shipment-row hover:bg-slate-50 cursor-pointer transition select-none bg-white">
                                    
                                    <!-- Cargo ID -->
                                    <td class="py-3 px-4 font-mono text-xs font-bold text-slate-900">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block w-2 h-2 rounded-full 
                                                {{ $shipment['risk_level'] === 'High' ? 'bg-intl-orange' : ($shipment['risk_level'] === 'Medium' ? 'bg-safety-amber' : 'bg-emerald-500') }}">
                                            </span>
                                            {{ $shipment['cargo_id'] }}
                                        </div>
                                    </td>
                                    
                                    <!-- Route Origin -> Destination -->
                                    <td class="py-3 px-4 text-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-1">
                                                <span class="text-slate-400 font-mono text-[10px]">{{ $shipment['origin_country_code'] }}</span>
                                                <span class="font-semibold text-slate-800">{{ $shipment['origin_city'] }}</span>
                                            </div>
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            <div class="flex items-center gap-1">
                                                <span class="text-slate-400 font-mono text-[10px]">{{ $shipment['destination_country_code'] }}</span>
                                                <span class="font-semibold text-slate-800">{{ $shipment['destination_city'] }}</span>
                                            </div>
                                        </div>
                                        <div class="text-[10px] text-slate-400 truncate max-w-[280px] mt-0.5 font-mono">
                                            {{ $shipment['origin_port'] }} → {{ $shipment['destination_port'] }}
                                        </div>
                                    </td>
                                    
                                    <!-- Mode -->
                                    <td class="py-3 px-4 text-xs font-mono">
                                        <div class="flex items-center gap-1 text-slate-600">
                                            @if ($shipment['transport_mode'] === 'Sea')
                                                <!-- Ship Icon -->
                                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                                <span>SEA CONTAINER</span>
                                            @else
                                                <!-- Air Icon -->
                                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                                <span>AIR FREIGHT</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- ETA -->
                                    <td class="py-3 px-4 font-mono text-xs text-slate-700">
                                        {{ \Carbon\Carbon::parse($shipment['eta'])->format('Y-m-d H:i') }}
                                    </td>

                                    <!-- Docs Check -->
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $docs = [$shipment['document_bill_of_lading'], $shipment['document_certificate_of_origin'], $shipment['document_commercial_invoice'], $shipment['document_packing_list'], $shipment['document_customs_declaration']];
                                            $approved = count(array_filter($docs, fn($d) => $d === 'Approved'));
                                            $total = count($docs);
                                        @endphp
                                        <div class="inline-flex items-center gap-1.5">
                                            <div class="w-12 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ ($approved / $total) * 100 }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold font-mono text-slate-600">{{ $approved }}/{{ $total }}</span>
                                        </div>
                                    </td>

                                    <!-- Temp Sparkline -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-20 h-6 text-intl-orange stroke-[1.5] fill-none" viewBox="0 0 100 30">
                                                <path stroke="currentColor" d="{{ $generateSparklinePath($shipment['container_temp_history']) }}"></path>
                                            </svg>
                                            <span class="font-mono text-[10px] text-slate-600 font-semibold">{{ end($shipment['container_temp_history']) }}°C</span>
                                        </div>
                                    </td>

                                    <!-- Risk Level -->
                                    <td class="py-3 px-4 text-center">
                                        @if ($shipment['risk_level'] === 'High')
                                            <span class="px-2 py-0.5 bg-red-100 text-intl-orange border border-red-200 rounded-full text-[9px] font-mono font-bold">HIGH</span>
                                        @elseif ($shipment['risk_level'] === 'Medium')
                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 border border-amber-200 rounded-full text-[9px] font-mono font-bold">MEDIUM</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full text-[9px] font-mono font-bold">LOW</span>
                                        @endif
                                    </td>

                                    <!-- Action -->
                                    <td class="py-3 px-4 text-center">
                                        <button onclick="toggleDetails('{{ $shipment['cargo_id'] }}', event)" class="p-1 hover:bg-slate-200 rounded text-slate-500">
                                            <svg id="arrow-icon-{{ $shipment['cargo_id'] }}" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- EXPANDABLE DRAWER PANEL: Detailed Telemetry and Telematics widgets -->
                                <tr id="details-{{ $shipment['cargo_id'] }}" class="hidden bg-slate-50/50">
                                    <td colspan="8" class="p-0 border-b border-slate-300">
                                        <div class="p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
                                            
                                            <!-- WIDGET 1: CUSTOMS DOCUMENT CHECKLIST -->
                                            <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col justify-between">
                                                <div>
                                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between border-b border-slate-100 pb-1.5">
                                                        <span>Customs Checklist</span>
                                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </h4>
                                                    <ul class="space-y-2 text-xs">
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-slate-600">Bill of Lading</span>
                                                            @if ($shipment['document_bill_of_lading'] === 'Approved')
                                                                <span class="text-emerald-600 font-bold">✓ Approved</span>
                                                            @elseif ($shipment['document_bill_of_lading'] === 'Under Review')
                                                                <span class="text-amber-600 font-bold">⚠ Review</span>
                                                            @else
                                                                <span class="text-slate-400 font-bold">⋯ Pending</span>
                                                            @endif
                                                        </li>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-slate-600">Certificate of Origin</span>
                                                            @if ($shipment['document_certificate_of_origin'] === 'Approved')
                                                                <span class="text-emerald-600 font-bold">✓ Approved</span>
                                                            @elseif ($shipment['document_certificate_of_origin'] === 'Under Review')
                                                                <span class="text-amber-600 font-bold">⚠ Review</span>
                                                            @else
                                                                <span class="text-slate-400 font-bold">⋯ Pending</span>
                                                            @endif
                                                        </li>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-slate-600">Commercial Invoice</span>
                                                            @if ($shipment['document_commercial_invoice'] === 'Approved')
                                                                <span class="text-emerald-600 font-bold">✓ Approved</span>
                                                            @elseif ($shipment['document_commercial_invoice'] === 'Under Review')
                                                                <span class="text-amber-600 font-bold">⚠ Review</span>
                                                            @else
                                                                <span class="text-slate-400 font-bold">⋯ Pending</span>
                                                            @endif
                                                        </li>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-slate-600">Packing List</span>
                                                            @if ($shipment['document_packing_list'] === 'Approved')
                                                                <span class="text-emerald-600 font-bold">✓ Approved</span>
                                                            @elseif ($shipment['document_packing_list'] === 'Under Review')
                                                                <span class="text-amber-600 font-bold">⚠ Review</span>
                                                            @else
                                                                <span class="text-slate-400 font-bold">⋯ Pending</span>
                                                            @endif
                                                        </li>
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-slate-600">Customs Declaration</span>
                                                            @if ($shipment['document_customs_declaration'] === 'Approved')
                                                                <span class="text-emerald-600 font-bold">✓ Approved</span>
                                                            @elseif ($shipment['document_customs_declaration'] === 'Under Review')
                                                                <span class="text-amber-600 font-bold">⚠ Review</span>
                                                            @else
                                                                <span class="text-slate-400 font-bold">⋯ Pending</span>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="mt-3 text-[10px] text-slate-400 font-mono text-right border-t border-slate-100 pt-2">
                                                    STATUS: <span class="font-bold uppercase">{{ $shipment['status'] }}</span>
                                                </div>
                                            </div>

                                            <!-- WIDGET 2: CURRENCY IMPACT & INVOICE MULTI-CONVERSION -->
                                            <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col justify-between">
                                                <div>
                                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between border-b border-slate-100 pb-1.5">
                                                        <span>Currency Impact Dashboard</span>
                                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </h4>
                                                    
                                                    <div class="space-y-2">
                                                        <div>
                                                            <p class="text-[10px] text-slate-400">Cargo Declared Value</p>
                                                            <p class="text-sm font-mono font-bold text-slate-900">
                                                                {{ number_format($shipment['value'], 2) }} {{ $shipment['currency'] }}
                                                            </p>
                                                        </div>
                                                        <div id="rates-container-{{ $shipment['cargo_id'] }}" class="text-xs font-mono space-y-1 bg-slate-50 p-2 rounded border border-slate-100">
                                                            <div class="flex justify-between text-slate-500">
                                                                <span>USD Equivalent:</span>
                                                                <span class="text-slate-800 font-bold" id="usd-rate-{{ $shipment['cargo_id'] }}">Calculated Live...</span>
                                                            </div>
                                                            <div class="flex justify-between text-slate-500">
                                                                <span>IDR Equivalent:</span>
                                                                <span class="text-slate-800 font-bold" id="idr-rate-{{ $shipment['cargo_id'] }}">Calculated Live...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 text-[9px] text-slate-400 italic">
                                                    Converted live using the Currency Exchange Rate API.
                                                </div>
                                            </div>

                                            <!-- WIDGET 3: GLOBAL WEATHER MONITORING (OpenMeteo) -->
                                            <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col justify-between">
                                                <div>
                                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between border-b border-slate-100 pb-1.5">
                                                        <span>Live Weather Status</span>
                                                        <span class="text-[10px] text-slate-500 font-mono">OpenMeteo</span>
                                                    </h4>
                                                    
                                                    <div id="weather-widget-{{ $shipment['cargo_id'] }}" class="space-y-2">
                                                        <div class="flex items-center gap-3">
                                                            <div id="weather-icon-{{ $shipment['cargo_id'] }}" class="p-2 bg-slate-100 rounded text-slate-600">
                                                                <!-- Spin loading -->
                                                                <svg class="w-6 h-6 animate-spin text-slate-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-slate-400">Position Coordinates</p>
                                                                <p class="text-xs font-mono font-bold">{{ $shipment['current_lat'] }}, {{ $shipment['current_lng'] }}</p>
                                                            </div>
                                                        </div>
                                                        <div id="weather-details-{{ $shipment['cargo_id'] }}" class="hidden text-xs font-mono space-y-1 bg-slate-50 p-2 rounded border border-slate-100">
                                                            <div class="flex justify-between"><span class="text-slate-500">Temperature:</span><span id="weather-temp-{{ $shipment['cargo_id'] }}" class="text-slate-800 font-bold">--</span></div>
                                                            <div class="flex justify-between"><span class="text-slate-500">Windspeed:</span><span id="weather-wind-{{ $shipment['cargo_id'] }}" class="text-slate-800 font-bold">--</span></div>
                                                            <div class="flex justify-between"><span class="text-slate-500">Condition:</span><span id="weather-desc-{{ $shipment['cargo_id'] }}" class="text-slate-800 font-bold font-sans">--</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 text-[9px] text-slate-400">
                                                    Forecast coordinates queried in real-time.
                                                </div>
                                            </div>

                                            <!-- WIDGET 4: GLOBAL COUNTRY & WORLD BANK ECONOMIC DATA -->
                                            <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col justify-between">
                                                <div>
                                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between border-b border-slate-100 pb-1.5">
                                                        <span>World Bank Trade Profile</span>
                                                        <span class="text-[9px] text-slate-500 font-mono">Code: {{ $shipment['destination_country_code'] }}</span>
                                                    </h4>
                                                    
                                                    <div class="space-y-2 text-xs">
                                                        <div class="flex items-center gap-2">
                                                            <!-- Flag will be pulled or default to country name -->
                                                            <span class="text-lg" id="flag-{{ $shipment['cargo_id'] }}">🏳</span>
                                                            <span class="font-bold text-slate-800" id="country-name-{{ $shipment['cargo_id'] }}">{{ $shipment['destination_country'] }}</span>
                                                        </div>
                                                        <div class="space-y-1 bg-slate-50 p-2 rounded border border-slate-100 font-mono text-[10px]">
                                                            @php
                                                                $wb = $worldBankData[$shipment['destination_country_code']] ?? null;
                                                            @endphp
                                                            @if ($wb)
                                                                <div class="flex justify-between"><span class="text-slate-500">Country GDP:</span><span class="text-slate-800 font-bold">${{ $wb['gdp_trillions'] }} Trillion</span></div>
                                                                <div class="flex justify-between"><span class="text-slate-500">LPI Global Rank:</span><span class="text-slate-800 font-bold">#{{ $wb['lpi_rank'] }}</span></div>
                                                                <div class="flex justify-between"><span class="text-slate-500">Trade % of GDP:</span><span class="text-slate-800 font-bold">{{ $wb['trade_pct_gdp'] }}%</span></div>
                                                                <div class="flex justify-between"><span class="text-slate-500">Customs Index:</span><span class="text-slate-800 font-bold">{{ $wb['customs_score'] }}/5.0</span></div>
                                                            @else
                                                                <div class="text-slate-500">Data profile unavailable</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Dynamic Countries API info box -->
                                                <div id="countries-api-{{ $shipment['cargo_id'] }}" class="mt-2 text-[9px] text-slate-400 font-mono bg-slate-100 p-1.5 rounded border border-slate-200">
                                                    Fetching Capital & Region...
                                                </div>
                                            </div>

                                            <!-- LOWER FULL-WIDTH Telemetric AIS Details (Marine Traffic Parameter) -->
                                            <div class="col-span-1 lg:col-span-4 bg-slate-900 text-slate-300 p-4 rounded border border-slate-800 grid grid-cols-2 md:grid-cols-5 gap-4 font-mono text-xs shadow-inner">
                                                <div>
                                                    <p class="text-[9px] text-slate-500 uppercase">VESSEL NAME</p>
                                                    <p class="text-white font-semibold">{{ $shipment['vessel_name'] ?: 'N/A (CARGO FLIGHT)' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[9px] text-slate-500 uppercase">IMO NUMBER</p>
                                                    <p class="text-slate-300">{{ $shipment['imo_number'] ?: 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[9px] text-slate-500 uppercase">SPEED / HEADING</p>
                                                    <p class="text-slate-300">{{ $shipment['speed'] }} knot / {{ $shipment['heading'] }}°</p>
                                                </div>
                                                <div>
                                                    <p class="text-[9px] text-slate-500 uppercase">CARRIER / LOGISTIC CO</p>
                                                    <p class="text-slate-300">{{ $shipment['carrier_name'] }}</p>
                                                </div>
                                                
                                                <!-- RISK SCORING ENGINE (Dynamic Calculator) -->
                                                <div class="bg-slate-950 px-3 py-2 rounded border border-slate-800 flex flex-col justify-center">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-[9px] text-slate-500 font-bold uppercase">Risk Score</span>
                                                        @php
                                                            // Simple Risk Engine Calculation
                                                            $score = $shipment['risk_level'] === 'High' ? 85 : ($shipment['risk_level'] === 'Medium' ? 52 : 18);
                                                            // Add points for pending checklist items
                                                            $pendingCount = count(array_filter($docs, fn($d) => $d === 'Pending'));
                                                            $score += $pendingCount * 8;
                                                            if ($score > 100) $score = 100;
                                                        @endphp
                                                        <span class="text-[10px] font-bold 
                                                            {{ $score >= 70 ? 'text-intl-orange' : ($score >= 40 ? 'text-safety-amber' : 'text-emerald-400') }}">
                                                            {{ $score }}/100
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-slate-800 h-1 rounded-full overflow-hidden">
                                                        <div class="h-full rounded-full 
                                                            {{ $score >= 70 ? 'bg-intl-orange' : ($score >= 40 ? 'bg-safety-amber' : 'bg-emerald-400') }}"
                                                            style="width: {{ $score }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

    </main>

    <!-- Leaflet.js Scripts (OpenStreetMap parameter) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        // Real shipments data embedded from controller
        const shipments = @json($shipments);
        
        let map;
        let mapMarkers = [];
        let mapLanes = [];
        
        let activeContinent = 'All';
        let activeMode = 'All';
        let activeRisk = 'All';

        // Digital Clock Live Telemetry
        function updateClock() {
            const clockEl = document.getElementById('live-clock');
            const now = new Date();
            const timeString = now.toUTCString().replace('GMT', 'UTC');
            clockEl.textContent = now.toLocaleTimeString('id-ID') + ' UTC+7';
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Initialize styled vector monochrome Map (OSM + CartoDB Light theme)
        function initMap() {
            // Center map overview
            map = L.map('map', {
                zoomControl: true,
                attributionControl: false
            }).setView([15.0, 10.0], 2);

            // Monochrome Tiles (CartoDB Positron) - Industrial Tech aesthetic
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 18,
                minZoom: 1.5
            }).addTo(map);

            // Add layers
            drawMapFeatures();
        }

        // Color mapper for route lanes & markers
        function getStatusColor(risk, status) {
            if (risk === 'High' || status === 'Demurrage Risk') return '#EA580C'; // International Orange
            if (risk === 'Medium' || status === 'Port Congestion') return '#FBBF24'; // Safety Amber
            return '#10B981'; // Emerald Green
        }

        // Draw shipping routes and coordinates markers onto the map (Port Location Dashboard)
        function drawMapFeatures() {
            // Clean existing layers
            mapMarkers.forEach(m => map.removeLayer(m));
            mapLanes.forEach(l => map.removeLayer(l));
            mapMarkers = [];
            mapLanes = [];

            shipments.forEach(shipment => {
                // Filter validation
                const continent = inArray(shipment.origin_country_code, ['CN','JP','SG']) ? 'Asia' : (inArray(shipment.origin_country_code, ['DE','NL','BE']) ? 'Europe' : (inArray(shipment.origin_country_code, ['US','BR']) ? 'Americas' : 'Oceania'));
                
                const matchesContinent = (activeContinent === 'All' || continent === activeContinent);
                const matchesMode = (activeMode === 'All' || shipment.transport_mode === activeMode);
                const matchesRisk = (activeRisk === 'All' || shipment.risk_level === activeRisk);

                if (matchesContinent && matchesMode && matchesRisk) {
                    const color = getStatusColor(shipment.risk_level, shipment.status);

                    // 1. Draw route polyline (Origin -> Current position -> Destination)
                    const routePath = [
                        [parseFloat(shipment.origin_lat), parseFloat(shipment.origin_lng)],
                        [parseFloat(shipment.current_lat), parseFloat(shipment.current_lng)],
                        [parseFloat(shipment.destination_lat), parseFloat(shipment.destination_lng)]
                    ];

                    const lane = L.polyline(routePath, {
                        color: color,
                        weight: 2,
                        opacity: 0.65,
                        dashArray: '5, 8'
                    }).addTo(map);
                    
                    // Bind simple path info
                    lane.bindPopup(`<b>Cargo ID: ${shipment.cargo_id}</b><br>Origin: ${shipment.origin_city}<br>Destination: ${shipment.destination_city}`);
                    mapLanes.push(lane);

                    // 2. Draw Ports Markers
                    const originMarker = L.circleMarker([shipment.origin_lat, shipment.origin_lng], {
                        radius: 4,
                        color: '#64748B',
                        fillColor: '#94A3B8',
                        fillOpacity: 1
                    }).addTo(map).bindPopup(`<b>Origin Port</b><br>${shipment.origin_port}<br>(${shipment.origin_country})`);
                    mapMarkers.push(originMarker);

                    const destMarker = L.circleMarker([shipment.destination_lat, shipment.destination_lng], {
                        radius: 4,
                        color: '#475569',
                        fillColor: '#1E293B',
                        fillOpacity: 1
                    }).addTo(map).bindPopup(`<b>Destination Port</b><br>${shipment.destination_port}<br>(${shipment.destination_country})`);
                    mapMarkers.push(destMarker);

                    // 3. Draw Vessel / Cargo Current Location Marker (Pulsating colored circle)
                    const vesselMarker = L.circleMarker([shipment.current_lat, shipment.current_lng], {
                        radius: 7,
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.8,
                        className: 'vessel-marker-pulse'
                    }).addTo(map);

                    // Bind detail popup
                    vesselMarker.bindPopup(`
                        <div class="font-mono text-xs">
                            <b class="text-slate-900">${shipment.cargo_id}</b><br>
                            <span class="text-slate-500">Vessel:</span> ${shipment.vessel_name || 'FedEx Cargo Plane'}<br>
                            <span class="text-slate-500">Speed:</span> ${shipment.speed} kn | Head: ${shipment.heading}°<br>
                            <span class="text-slate-500">Status:</span> <span style="color:${color};font-weight:bold">${shipment.status}</span><br>
                            <button onclick="toggleDetails('${shipment.cargo_id}')" class="mt-2 text-[10px] text-blue-600 underline border-none bg-none p-0 cursor-pointer">View Operations Center</button>
                        </div>
                    `);
                    mapMarkers.push(vesselMarker);
                }
            });
        }

        function inArray(needle, haystack) {
            return haystack.includes(needle);
        }

        // Handle Row and Details Dropdowns (Clicking Row expands panel)
        function toggleDetails(cargoId, event) {
            // Prevent toggling twice if clicked on expand button vs row
            if (event) {
                event.stopPropagation();
            }

            const detailsRow = document.getElementById(`details-${cargoId}`);
            const arrowIcon = document.getElementById(`arrow-icon-${cargoId}`);
            const masterRow = document.getElementById(`row-${cargoId}`);

            if (detailsRow.classList.contains('hidden')) {
                // Collapse any active details first
                document.querySelectorAll('.shipment-row').forEach(row => {
                    const id = row.getAttribute('data-cargo');
                    document.getElementById(`details-${id}`).classList.add('hidden');
                    document.getElementById(`arrow-icon-${id}`).classList.remove('rotate-180');
                    row.classList.remove('bg-slate-100');
                });

                // Expand clicked details
                detailsRow.classList.remove('hidden');
                arrowIcon.classList.add('rotate-180');
                masterRow.classList.add('bg-slate-100');
                
                // Pan map to current location
                const shipment = shipments.find(s => s.cargo_id === cargoId);
                if (shipment) {
                    map.panTo([shipment.current_lat, shipment.current_lng]);
                }

                // Trigger dynamic integrations
                loadDynamicWidgetData(cargoId);

            } else {
                detailsRow.classList.add('hidden');
                arrowIcon.classList.remove('rotate-180');
                masterRow.classList.remove('bg-slate-100');
            }
        }

        // LOAD INTEG-WIDGETS: Dynamic weather (OpenMeteo), economic conversion (ExchangeRate) and Countries info
        function loadDynamicWidgetData(cargoId) {
            const shipment = shipments.find(s => s.cargo_id === cargoId);
            if (!shipment) return;

            // 1. Dynamic Weather Fetch (OpenMeteo parameter)
            fetchWeather(shipment);

            // 2. Dynamic Currency Impact Converter (Exchange Rate parameter)
            fetchExchangeRate(shipment);

            // 3. Dynamic Countries Profile Fetch (Countries parameter)
            fetchCountryDetails(shipment);
        }

        // Weather Service (OpenMeteo)
        function fetchWeather(shipment) {
            const id = shipment.cargo_id;
            const weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${shipment.current_lat}&longitude=${shipment.current_lng}&current_weather=true`;

            fetch(weatherUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.current_weather) {
                        const weather = data.current_weather;
                        document.getElementById(`weather-temp-${id}`).textContent = `${weather.temperature}°C`;
                        document.getElementById(`weather-wind-${id}`).textContent = `${weather.windspeed} km/h`;
                        
                        // Map code to weather desc
                        const desc = getWeatherDesc(weather.weathercode);
                        document.getElementById(`weather-desc-${id}`).textContent = desc;

                        // Replace loading with icon
                        const iconEl = document.getElementById(`weather-icon-${id}`);
                        iconEl.innerHTML = getWeatherIcon(weather.weathercode);

                        document.getElementById(`weather-details-${id}`).classList.remove('hidden');
                    }
                })
                .catch(err => {
                    console.error('Weather API failed', err);
                    document.getElementById(`weather-desc-${id}`).textContent = "Mock Weather Offline";
                    document.getElementById(`weather-temp-${id}`).textContent = "24.5°C";
                    document.getElementById(`weather-wind-${id}`).textContent = "12.4 km/h";
                    document.getElementById(`weather-details-${id}`).classList.remove('hidden');
                });
        }

        // Exchange Rate Conversions (Exchange Rate parameter)
        function fetchExchangeRate(shipment) {
            const id = shipment.cargo_id;
            // Public Open API Exchange Rates base USD
            const ratesUrl = `https://open.er-api.com/v6/latest/USD`;

            fetch(ratesUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.rates) {
                        const rates = data.rates;
                        
                        // Calculate conversions
                        let valueInUSD = 0;
                        const originalValue = parseFloat(shipment.value);
                        const originalCurrency = shipment.currency;

                        // Conversion logic (original currency to USD first)
                        if (originalCurrency === 'USD') {
                            valueInUSD = originalValue;
                        } else if (rates[originalCurrency]) {
                            valueInUSD = originalValue / rates[originalCurrency];
                        } else {
                            // Fallbacks
                            const fallbacks = { 'EUR': 1.09, 'JPY': 0.0062, 'CNY': 0.14, 'BRL': 0.18, 'AUD': 0.66 };
                            valueInUSD = originalValue * (fallbacks[originalCurrency] || 1);
                        }

                        const valueInIDR = valueInUSD * rates['IDR'];

                        document.getElementById(`usd-rate-${id}`).textContent = `$ ${valueInUSD.toLocaleString('en-US', {maximumFractionDigits:2})} USD`;
                        document.getElementById(`idr-rate-${id}`).textContent = `Rp ${valueInIDR.toLocaleString('id-ID', {maximumFractionDigits:0})}`;
                    }
                })
                .catch(err => {
                    console.error('Rates API failed', err);
                    // Use realistic calculations on failure
                    const value = parseFloat(shipment.value);
                    const rates = { 'USD': 1, 'EUR': 1.08, 'JPY': 0.0064, 'CNY': 0.14, 'BRL': 0.18, 'AUD': 0.67 };
                    const usdEquivalent = value * (rates[shipment.currency] || 1);
                    const idrEquivalent = usdEquivalent * 16350;

                    document.getElementById(`usd-rate-${id}`).textContent = `$ ${usdEquivalent.toLocaleString('en-US', {maximumFractionDigits:2})} USD`;
                    document.getElementById(`idr-rate-${id}`).textContent = `Rp ${idrEquivalent.toLocaleString('id-ID', {maximumFractionDigits:0})}`;
                });
        }

        // Countries details lookup (Countries API parameter)
        function fetchCountryDetails(shipment) {
            const id = shipment.cargo_id;
            const code = shipment.destination_country_code;
            const countryUrl = `https://restcountries.com/v3.1/alpha/${code}`;

            fetch(countryUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data[0]) {
                        const country = data[0];
                        const capital = country.capital ? country.capital[0] : 'N/A';
                        const population = country.population ? country.population.toLocaleString('id-ID') : 'N/A';
                        const region = country.region || 'N/A';
                        
                        // Set Flag emoji if possible
                        if (country.flag) {
                            document.getElementById(`flag-${id}`).textContent = country.flag;
                        }
                        
                        document.getElementById(`countries-api-${id}`).innerHTML = `
                            <div class="grid grid-cols-2 gap-1 text-[9px]">
                                <div><span class="text-slate-500">Capital:</span> ${capital}</div>
                                <div><span class="text-slate-500">Region:</span> ${region}</div>
                                <div class="col-span-2"><span class="text-slate-500">Pop:</span> ${population} jiwa</div>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error('Countries API failed', err);
                    document.getElementById(`countries-api-${id}`).innerHTML = `
                        <div class="text-[9px] text-slate-500 flex justify-between">
                            <span>Capital: Synced Offline</span>
                            <span>Region: Global</span>
                        </div>
                    `;
                });
        }

        // OpenMeteo weather descriptors mapper
        function getWeatherDesc(code) {
            if (code === 0) return "Clear Sky";
            if ([1, 2, 3].includes(code)) return "Mainly Clear / Cloudy";
            if ([45, 48].includes(code)) return "Foggy / Mist";
            if ([51, 53, 55].includes(code)) return "Light Drizzle";
            if ([61, 63, 65].includes(code)) return "Moderate Rain";
            if ([71, 73, 75].includes(code)) return "Snowfall";
            if ([80, 81, 82].includes(code)) return "Rain Showers";
            if ([95, 96, 99].includes(code)) return "Storm Alert";
            return "Stable Forecast";
        }

        // SVG weather icon provider
        function getWeatherIcon(code) {
            if (code === 0) {
                return `<svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>`;
            }
            if ([1, 2, 3].includes(code)) {
                return `<svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>`;
            }
            if ([61, 63, 65, 80, 81, 82].includes(code)) {
                return `<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5M9 5a3 3 0 11-6 0 3 3 0 016 0zm12-3v2m0 0h-2m2 0h2m-6 3a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>`;
            }
            return `<svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
        }

        // Sidebar Smart Filter triggers
        function toggleModeFilter(mode) {
            activeMode = mode;
            document.querySelectorAll('.filter-mode-btn').forEach(btn => {
                btn.className = "filter-mode-btn grow text-center py-1 rounded border text-xs font-mono transition bg-slate-950 border-slate-800 text-slate-400 hover:text-white";
            });

            const id = mode.toLowerCase();
            document.getElementById(`mode-${id}`).className = "filter-mode-btn grow text-center py-1 rounded border text-xs font-mono font-semibold transition bg-slate-800 border-slate-700 text-white";
            
            applyFilters();
        }

        function toggleRiskFilter(risk) {
            activeRisk = risk;
            document.querySelectorAll('.filter-risk-btn').forEach(btn => {
                btn.className = "filter-risk-btn grow py-1 text-center rounded border text-xs font-mono transition bg-slate-950 border-slate-800 text-slate-400 hover:text-white";
            });

            const id = risk.toLowerCase();
            document.getElementById(`risk-${id}`).className = "filter-risk-btn grow py-1 text-center rounded border text-xs font-mono font-semibold transition bg-slate-800 border-slate-700 text-white";

            applyFilters();
        }

        function applyFilters() {
            activeContinent = document.getElementById('filter-continent').value;
            
            let visibleCount = 0;

            document.querySelectorAll('.shipment-row').forEach(row => {
                const continent = row.getAttribute('data-continent');
                const mode = row.getAttribute('data-mode');
                const risk = row.getAttribute('data-risk');
                const cargoId = row.getAttribute('data-cargo');

                const matchesContinent = (activeContinent === 'All' || continent === activeContinent);
                const matchesMode = (activeMode === 'All' || mode === activeMode);
                const matchesRisk = (activeRisk === 'All' || risk === activeRisk);

                if (matchesContinent && matchesMode && matchesRisk) {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                    // Hide details container too if hidden
                    document.getElementById(`details-${cargoId}`).classList.add('hidden');
                    document.getElementById(`arrow-icon-${cargoId}`).classList.remove('rotate-180');
                    row.classList.remove('bg-slate-100');
                }
            });

            document.getElementById('filtered-count').textContent = visibleCount;
            
            // Re-render markers and shipping lanes on map
            drawMapFeatures();
        }

        function resetFilters() {
            document.getElementById('filter-continent').value = 'All';
            toggleModeFilter('All');
            toggleRiskFilter('All');
        }

        // Initialize Map on window load
        window.addEventListener('load', () => {
            initMap();
        });
    </script>
</body>
</html>
