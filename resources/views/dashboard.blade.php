<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VORTEX // Global Supply Chain & Logistics Control</title>
    <!-- Fonts: Inter & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind & App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-slate-100 bg-slate-950 flex flex-col min-h-screen overflow-x-hidden selection:bg-orange-600 selection:text-white"
      x-data="dashboardApp()">

    <!-- Header Block -->
    <header class="border-b border-slate-800 bg-slate-900 px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 z-10">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded border border-orange-600 bg-slate-950 flex items-center justify-center relative overflow-hidden">
                <span class="text-orange-600 font-mono font-bold text-lg select-none">V</span>
                <div class="absolute inset-0 bg-orange-600/10 animate-pulse"></div>
            </div>
            <div>
                <h1 class="text-lg font-bold tracking-wider font-mono text-slate-100 flex items-center gap-2">
                    VORTEX <span class="text-slate-500">//</span> <span class="text-slate-300 font-sans font-medium text-sm tracking-normal">GLOBAL LOGISTICS CONTROL</span>
                </h1>
                <p class="text-xs text-slate-400 font-mono tracking-tight mt-0.5">SYS_REF: VX-990-TRANSIT</p>
            </div>
        </div>

        <!-- Exchange Ticker (Parameter: Exchange Rate) -->
        <div class="hidden lg:flex items-center gap-4 bg-slate-950/60 border border-slate-800 px-4 py-2 rounded text-xs font-mono text-slate-400">
            <div class="flex items-center gap-1 border-r border-slate-800 pr-3">
                <span class="text-slate-500 uppercase">Currency Ticker</span>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-300">USD/EUR</span>
                    <span class="text-slate-200">0.92</span>
                    <span class="text-green-500 text-[10px]">▲ +0.08%</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-300">USD/CNY</span>
                    <span class="text-slate-200">7.24</span>
                    <span class="text-red-500 text-[10px]">▼ -0.12%</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-300">USD/IDR</span>
                    <span class="text-slate-200">16,345</span>
                    <span class="text-green-500 text-[10px]">▲ +0.24%</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-300">USD/AUD</span>
                    <span class="text-slate-200">1.49</span>
                    <span class="text-slate-500 text-[10px]">-- 0.00%</span>
                </div>
            </div>
        </div>

        <!-- System Clock & Status -->
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="text-xs text-slate-400 font-mono" x-text="systemTime"></div>
                <div class="text-[10px] text-slate-500 font-mono tracking-wider">SECURE GRID // UTC+7</div>
            </div>
            <div class="flex items-center gap-2 bg-slate-950 px-3 py-1.5 rounded border border-slate-800">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[10px] font-mono text-slate-300 tracking-wider">ONLINE</span>
            </div>
        </div>
    </header>

    <!-- Main Dashboard Body Grid -->
    <main class="flex-1 grid grid-cols-1 xl:grid-cols-4 gap-6 p-6 overflow-hidden">
        
        <!-- Left Panel: Stats, Smart Filters, Logistics News -->
        <section class="xl:col-span-1 flex flex-col gap-6">
            
            <!-- Global Overview Statistics (Left Panel) -->
            <div class="bg-slate-900 border border-slate-800 rounded p-4 flex flex-col gap-4">
                <div class="border-b border-slate-800 pb-2 flex items-center justify-between">
                    <h2 class="font-mono text-xs font-semibold text-slate-300 tracking-wider uppercase">Global Overview</h2>
                    <span class="text-[10px] font-mono bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">REALTIME</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <!-- Stat Card 1 -->
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded p-3 flex flex-col gap-1 text-center">
                        <span class="text-[10px] text-slate-500 font-mono uppercase tracking-tight">Active Cargo</span>
                        <span class="text-xl font-bold font-mono text-slate-100" x-text="shipments.length">--</span>
                        <span class="text-[9px] text-slate-400 font-mono">In-Transit</span>
                    </div>
                    <!-- Stat Card 2 -->
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded p-3 flex flex-col gap-1 text-center">
                        <span class="text-[10px] text-slate-500 font-mono uppercase tracking-tight">Cleared</span>
                        <span class="text-xl font-bold font-mono text-green-500">942</span>
                        <span class="text-[9px] text-slate-500 font-mono">This Month</span>
                    </div>
                    <!-- Stat Card 3 -->
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded p-3 flex flex-col gap-1 text-center">
                        <span class="text-[10px] text-slate-500 font-mono uppercase tracking-tight">Anomalies</span>
                        <span class="text-xl font-bold font-mono text-orange-500" x-text="activeAnomaliesCount()">0</span>
                        <span class="text-[9px] text-orange-600 font-mono font-semibold">Action Req</span>
                    </div>
                </div>
            </div>

            <!-- Smart Filters Panel (Left Panel) -->
            <div class="bg-slate-900 border border-slate-800 rounded p-4 flex flex-col gap-4">
                <div class="border-b border-slate-800 pb-2 flex items-center justify-between">
                    <h2 class="font-mono text-xs font-semibold text-slate-300 tracking-wider uppercase">Smart Filters</h2>
                    <button @click="resetFilters()" class="text-[10px] font-mono text-orange-500 hover:text-orange-400 transition">Reset</button>
                </div>
                
                <div class="flex flex-col gap-3.5">
                    <!-- Continent Select -->
                    <div>
                        <label class="text-[10px] font-mono text-slate-400 uppercase tracking-wider block mb-1.5">Continent Destination</label>
                        <div class="grid grid-cols-2 gap-1 bg-slate-950 p-1 rounded border border-slate-800 text-center text-xs font-mono">
                            <template x-for="cont in ['All', 'Asia', 'Europe', 'America']">
                                <button type="button" 
                                        @click="filters.continent = cont"
                                        :class="filters.continent === cont ? 'bg-slate-800 text-orange-500 border-slate-700 font-medium' : 'text-slate-400 border-transparent'"
                                        class="py-1 rounded border text-[11px] transition-colors"
                                        x-text="cont"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Transit Mode -->
                    <div>
                        <label class="text-[10px] font-mono text-slate-400 uppercase tracking-wider block mb-1.5">Transit Mode</label>
                        <div class="grid grid-cols-3 gap-1 bg-slate-950 p-1 rounded border border-slate-800 text-center text-xs font-mono">
                            <template x-for="md in ['All', 'Sea', 'Air']">
                                <button type="button" 
                                        @click="filters.mode = md"
                                        :class="filters.mode === md ? 'bg-slate-800 text-orange-500 border-slate-700 font-medium' : 'text-slate-400 border-transparent'"
                                        class="py-1 rounded border text-[11px] transition-colors"
                                        x-text="md"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Risk Level -->
                    <div>
                        <label class="text-[10px] font-mono text-slate-400 uppercase tracking-wider block mb-1.5">Risk Rating</label>
                        <select x-model="filters.risk" class="w-full bg-slate-950 border border-slate-800 px-3 py-2 rounded text-xs font-mono text-slate-300 focus:outline-none focus:border-slate-600 transition">
                            <option value="All">All Risk Ratings</option>
                            <option value="Low">Low Risk (Green)</option>
                            <option value="Moderate">Moderate Risk (Amber)</option>
                            <option value="Critical">Critical Risk (Red)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- News Feed Widget (Parameter: News API / GNews) -->
            <div class="bg-slate-900 border border-slate-800 rounded p-4 flex-1 flex flex-col gap-3 min-h-[220px]">
                <div class="border-b border-slate-800 pb-2 flex items-center justify-between">
                    <h2 class="font-mono text-xs font-semibold text-slate-300 tracking-wider uppercase flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"></path>
                        </svg>
                        Global Logistics News
                    </h2>
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-600 animate-pulse"></span>
                </div>
                
                <div class="flex-1 overflow-y-auto space-y-3 pr-1 text-xs">
                    <template x-for="item in newsList">
                        <div class="border-b border-slate-800/60 pb-2.5 last:border-b-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="font-mono text-[9px] font-semibold text-slate-500 uppercase" x-text="item.source"></span>
                                <span class="font-mono text-[9px] text-slate-500" x-text="item.time"></span>
                            </div>
                            <h3 class="text-slate-300 font-medium leading-relaxed hover:text-orange-500 transition duration-150 cursor-pointer" x-text="item.title"></h3>
                            <p class="text-slate-400 text-[11px] leading-relaxed mt-1" x-text="item.summary"></p>
                        </div>
                    </template>
                </div>
            </div>

        </section>

        <!-- Right/Center Content Area: Leaflet Map & Interactive Cargo Table -->
        <section class="xl:col-span-3 flex flex-col gap-6">
            
            <!-- Leaflet Interactive Map Card -->
            <div class="bg-slate-900 border border-slate-800 rounded flex flex-col overflow-hidden relative">
                <!-- Map Header Status Overlay -->
                <div class="absolute top-3 left-3 bg-slate-900/90 border border-slate-700 px-3 py-1.5 rounded text-xs font-mono text-slate-300 z-[1000] flex items-center gap-2 shadow-lg max-w-sm backdrop-blur-sm">
                    <span class="text-orange-600 font-bold">// ROUTE STATUS:</span>
                    <span x-text="selectedCargoId ? 'DISPLAYING CARGO: ' + selectedCargoId : 'ALL FLIGHTS & SHIPPING CHANNELS ACTIVE'"></span>
                </div>

                <div class="absolute bottom-3 right-3 bg-slate-900/90 border border-slate-800 px-3 py-2 rounded text-[10px] font-mono text-slate-400 z-[1000] flex flex-col gap-1 backdrop-blur-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-1 bg-green-500 rounded-full inline-block"></span>
                        <span>Low Risk (On Schedule)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-1 bg-amber-500 rounded-full inline-block"></span>
                        <span>Moderate Risk (Port Congestion)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-1 bg-orange-600 rounded-full inline-block"></span>
                        <span>Critical Risk (Customs Anomaly)</span>
                    </div>
                </div>

                <!-- Leaflet Mount Point -->
                <div id="map" class="h-[360px] md:h-[420px] w-full"></div>
            </div>

            <!-- Cargo Data Table Card -->
            <div class="bg-slate-900 border border-slate-800 rounded flex flex-col">
                <div class="border-b border-slate-800 px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-orange-600 font-mono">//</span>
                        <h2 class="font-mono text-xs font-semibold text-slate-300 tracking-wider uppercase">Active Shipments Log</h2>
                    </div>
                    <div class="text-xs text-slate-400 font-mono">
                        Showing <span class="text-slate-200" x-text="filteredShipments().length"></span> active cargo logs
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-950/60 border-b border-slate-800 text-[10px] font-mono text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-4 w-6"></th>
                                <th class="py-3 px-4">Cargo ID</th>
                                <th class="py-3 px-4">Logistics Route (Origin ➔ Destination)</th>
                                <th class="py-3 px-4">Transit Mode</th>
                                <th class="py-3 px-4">Estimated ETA</th>
                                <th class="py-3 px-4">Cargo Value</th>
                                <th class="py-3 px-4">Status & Action</th>
                                <th class="py-3 px-4 text-center">Temp Sparkline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in filteredShipments()" :key="item.id">
                                <tr :class="selectedCargoId === item.id ? 'bg-slate-800/40 border-l-2 border-l-orange-600' : 'hover:bg-slate-800/10'" 
                                    class="border-b border-slate-800 transition duration-150">
                                    
                                    <!-- Expand Row Button -->
                                    <td class="py-3 px-4 text-center">
                                        <button @click="toggleSelectCargo(item.id)" class="text-slate-400 hover:text-slate-100 transition focus:outline-none">
                                            <svg class="w-4 h-4 transform transition-transform" 
                                                 :class="selectedCargoId === item.id ? 'rotate-90 text-orange-500' : ''" 
                                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </td>

                                    <!-- Cargo ID -->
                                    <td class="py-3 px-4 font-mono font-semibold text-slate-300" x-text="item.id"></td>
                                    
                                    <!-- Route (Origin ➔ Destination) -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-slate-200" x-text="item.origin.name"></span>
                                            <span class="text-slate-500">➔</span>
                                            <div class="flex items-center gap-1">
                                                <span class="font-semibold text-slate-200" x-text="item.destination.name"></span>
                                                <span class="text-[10px] bg-slate-800 text-slate-400 font-mono px-1 rounded" x-text="item.destination.code"></span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Transit Mode -->
                                    <td class="py-3 px-4 font-mono">
                                        <div class="flex items-center gap-1.5">
                                            <!-- Sea Icon -->
                                            <template x-if="item.mode === 'Sea'">
                                                <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                                </svg>
                                            </template>
                                            <!-- Air Icon -->
                                            <template x-if="item.mode === 'Air'">
                                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                            </template>
                                            <span x-text="item.mode"></span>
                                        </div>
                                    </td>

                                    <!-- ETA -->
                                    <td class="py-3 px-4 font-mono text-slate-300" x-text="item.eta"></td>

                                    <!-- Value -->
                                    <td class="py-3 px-4 font-mono text-slate-300" x-text="formatCurrency(item.value)"></td>

                                    <!-- Status -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-between gap-1.5">
                                            <span :class="statusBadgeColor(item.status)" 
                                                  class="px-2 py-0.5 rounded-full text-[10px] font-mono font-medium tracking-tight uppercase"
                                                  x-text="item.status"></span>
                                            
                                            <!-- Indicator Dot -->
                                            <span class="relative flex h-2 w-2">
                                                <span :class="riskPulseColor(item.risk)" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                                                <span :class="riskDotColor(item.risk)" class="relative inline-flex rounded-full h-2 w-2"></span>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Temperature Sparkline (Parameter: container telemetrics) -->
                                    <td class="py-3 px-4 text-center">
                                        <div class="inline-block w-24 h-6">
                                            <svg viewBox="0 0 100 30" class="w-full h-full stroke-2 fill-none overflow-visible">
                                                <!-- Graph Sparkline Path -->
                                                <path :d="generateSparklinePath(item.sparklineData)" 
                                                      :class="item.risk === 'Critical' ? 'stroke-orange-600' : 'stroke-sky-500'" 
                                                      stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Expandable Details Row -->
                                <tr x-show="selectedCargoId === item.id" 
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-[-10px]"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="bg-slate-950/80 border-b border-slate-800">
                                    <td colspan="8" class="p-6">
                                        
                                        <!-- Main Details Subgrid -->
                                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                            
                                            <!-- Telemetry, Weather & World Bank Details -->
                                            <div class="flex flex-col gap-4">
                                                <div class="border-b border-slate-800 pb-1.5">
                                                    <h3 class="font-mono text-xs text-orange-500 uppercase tracking-wider">Cargo & Port Analytics</h3>
                                                </div>
                                                
                                                <div class="space-y-3 font-mono text-[11px]">
                                                    <!-- Telemetry -->
                                                    <div class="bg-slate-900 border border-slate-800/80 p-3 rounded space-y-1.5">
                                                        <div class="text-slate-400 border-b border-slate-800 pb-1 text-[10px] uppercase font-bold tracking-wider flex items-center justify-between">
                                                            <span>Vessel Tracking (Marine Traffic)</span>
                                                            <span class="text-sky-500">SYS_OK</span>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-y-1">
                                                            <span class="text-slate-500">Carrier / Flight:</span>
                                                            <span class="text-slate-300 font-semibold text-right" x-text="item.telemetry.carrier"></span>
                                                            <span class="text-slate-500">MMSI / ID:</span>
                                                            <span class="text-slate-300 text-right" x-text="item.telemetry.mmsi"></span>
                                                            <span class="text-slate-500">Speed / Heading:</span>
                                                            <span class="text-slate-300 text-right"><span x-text="item.telemetry.speed"></span> kt / <span x-text="item.telemetry.heading"></span>°</span>
                                                            <span class="text-slate-500">Congestion Index:</span>
                                                            <span class="text-right" :class="item.telemetry.congestion > 7 ? 'text-orange-500' : 'text-green-500'" x-text="item.telemetry.congestion + ' / 10'"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Weather (Open Meteo) -->
                                                    <div class="bg-slate-900 border border-slate-800/80 p-3 rounded space-y-1.5">
                                                        <div class="text-slate-400 border-b border-slate-800 pb-1 text-[10px] uppercase font-bold tracking-wider flex items-center justify-between">
                                                            <span>Destination Weather (Open Meteo)</span>
                                                            <span class="text-slate-500" x-text="item.weather.time"></span>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-y-1">
                                                            <span class="text-slate-500">Destination:</span>
                                                            <span class="text-slate-300 text-right font-semibold" x-text="item.destination.name"></span>
                                                            <span class="text-slate-500">Temperature:</span>
                                                            <span class="text-slate-300 text-right" x-text="item.weather.temp + ' °C'"></span>
                                                            <span class="text-slate-500">Wind Telemetry:</span>
                                                            <span class="text-slate-300 text-right" x-text="item.weather.wind + ' km/h'"></span>
                                                            <span class="text-slate-500">Forecast Code:</span>
                                                            <span class="text-slate-300 text-right" x-text="item.weather.condition"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Economic Stats (World Bank) -->
                                                    <div class="bg-slate-900 border border-slate-800/80 p-3 rounded space-y-1.5">
                                                        <div class="text-slate-400 border-b border-slate-800 pb-1 text-[10px] uppercase font-bold tracking-wider flex items-center justify-between">
                                                            <span>Economic Profile (World Bank)</span>
                                                            <span class="text-slate-500">DATA_V2</span>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-y-1">
                                                            <span class="text-slate-500">Dest GDP (Nominal):</span>
                                                            <span class="text-slate-300 text-right" x-text="item.worldBank.gdp"></span>
                                                            <span class="text-slate-500">LPI Score (World Bank):</span>
                                                            <span class="text-slate-300 text-right"><span class="font-bold" x-text="item.worldBank.lpi"></span> <span class="text-[10px] text-slate-500" x-text="'(Rank ' + item.worldBank.lpiRank + ')'"></span></span>
                                                            <span class="text-slate-500">Trade Risk Index:</span>
                                                            <span class="text-right font-semibold" :class="item.worldBank.riskIndex === 'Critical' ? 'text-red-500' : (item.worldBank.riskIndex === 'Moderate' ? 'text-amber-500' : 'text-green-500')" x-text="item.worldBank.riskIndex"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Customs Document Checklist -->
                                            <div class="flex flex-col gap-4">
                                                <div class="border-b border-slate-800 pb-1.5 flex items-center justify-between">
                                                    <h3 class="font-mono text-xs text-orange-500 uppercase tracking-wider">Customs Document Checklist</h3>
                                                    <span class="text-[9px] bg-slate-800 text-slate-400 px-1 font-mono">5 REQUIREMENT</span>
                                                </div>
                                                
                                                <div class="space-y-2 text-xs">
                                                    <template x-for="doc in item.documents" :key="doc.name">
                                                        <div class="bg-slate-900 border border-slate-800 p-2.5 rounded flex items-center justify-between gap-4">
                                                            <div class="flex items-center gap-2">
                                                                <!-- Checked Icon -->
                                                                <template x-if="doc.status === 'Approved'">
                                                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </template>
                                                                <!-- Alert Icon -->
                                                                <template x-if="doc.status === 'Warning'">
                                                                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                    </svg>
                                                                </template>
                                                                <!-- Pending Icon -->
                                                                <template x-if="doc.status === 'Pending'">
                                                                    <svg class="w-4 h-4 text-slate-500 flex-shrink-0 animate-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </template>

                                                                <div>
                                                                    <p class="font-mono text-xs font-semibold text-slate-300" x-text="doc.name"></p>
                                                                    <p class="text-[10px] text-slate-500 font-mono" x-text="doc.ref"></p>
                                                                </div>
                                                            </div>

                                                            <div class="flex items-center gap-2">
                                                                <span :class="doc.status === 'Approved' ? 'text-green-500 bg-green-500/10 border-green-500/30' : (doc.status === 'Warning' ? 'text-amber-500 bg-amber-500/10 border-amber-500/30' : 'text-slate-500 bg-slate-900 border-slate-800')"
                                                                      class="px-2 py-0.5 rounded border text-[9px] font-mono"
                                                                      x-text="doc.status"></span>
                                                                
                                                                <!-- Toggle Button (Mock Change Action) -->
                                                                <button @click="toggleDocStatus(item.id, doc.name)" 
                                                                        class="text-slate-400 hover:text-orange-500 text-[10px] font-mono border border-slate-700 hover:border-orange-500/50 px-1.5 py-0.5 rounded transition">
                                                                    Verify
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Currency Converter & Risk Actions -->
                                            <div class="flex flex-col gap-4">
                                                <div class="border-b border-slate-800 pb-1.5">
                                                    <h3 class="font-mono text-xs text-orange-500 uppercase tracking-wider">Exchange & Risk Operations</h3>
                                                </div>

                                                <div class="space-y-4">
                                                    <!-- Currency Converter Calculator Widget (Exchange Rate) -->
                                                    <div class="bg-slate-900 border border-slate-800 p-4 rounded space-y-3">
                                                        <h4 class="text-[10px] font-mono uppercase font-bold text-slate-400 tracking-wider">Currency Exchange Calculator</h4>
                                                        <div class="grid grid-cols-3 gap-2">
                                                            <div class="col-span-2">
                                                                <input type="number" 
                                                                       x-model.number="calcValue"
                                                                       class="w-full bg-slate-950 border border-slate-800 px-3 py-1.5 rounded text-xs font-mono text-slate-200 focus:outline-none focus:border-slate-600 transition" />
                                                            </div>
                                                            <div class="col-span-1">
                                                                <select x-model="calcCurrency" class="w-full h-full bg-slate-950 border border-slate-800 px-2 py-1 rounded text-xs font-mono text-slate-300 focus:outline-none transition">
                                                                    <option value="EUR">EUR (€)</option>
                                                                    <option value="IDR">IDR (Rp)</option>
                                                                    <option value="CNY">CNY (¥)</option>
                                                                    <option value="AUD">AUD ($)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-between text-xs font-mono pt-1">
                                                            <span class="text-slate-500">Valuation:</span>
                                                            <span class="text-slate-300 font-semibold">USD <span x-text="formatCurrency(calcValue)"></span></span>
                                                        </div>
                                                        <div class="flex items-center justify-between text-xs font-mono pt-1 border-t border-slate-800/80">
                                                            <span class="text-slate-500">Converted Value:</span>
                                                            <span class="text-orange-500 font-bold" x-text="formatConvertedValue(calcValue, calcCurrency)"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Incident Action Card -->
                                                    <div class="border border-slate-800 p-4 rounded bg-slate-900 flex flex-col gap-3">
                                                        <div class="flex items-center justify-between">
                                                            <h4 class="text-[10px] font-mono uppercase font-bold text-slate-400 tracking-wider">Operational Directives</h4>
                                                            <span :class="item.risk === 'Critical' ? 'bg-orange-600/10 text-orange-500' : 'bg-green-500/10 text-green-500'" 
                                                                  class="text-[9px] font-mono px-1.5 py-0.5 rounded border border-transparent"
                                                                  x-text="item.risk === 'Critical' ? 'THREAT ACTIVE' : 'RISK COMPLIANT'"></span>
                                                        </div>
                                                        <p class="text-[11px] text-slate-400 leading-relaxed font-mono">
                                                            Operational directives for <span class="text-slate-300 font-semibold" x-text="item.id"></span>:
                                                            <span x-text="item.risk === 'Critical' ? 'Document discrepancies detected. Reroute ship to auxiliary custom zone. Hold freight forwarding approvals.' : 'Normal operation. Clear cargo through default logistic lane.'"></span>
                                                        </p>
                                                        
                                                        <div class="grid grid-cols-2 gap-2 mt-1">
                                                            <button @click="dispatchAlert(item.id)" 
                                                                    class="bg-orange-600 hover:bg-orange-700 text-white font-semibold font-mono text-[10px] py-2 rounded text-center transition tracking-wide uppercase">
                                                                Dispatch Alert
                                                            </button>
                                                            <button @click="updateShipmentStatus(item.id, 'Customs Cleared')"
                                                                    class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold font-mono text-[10px] py-2 rounded text-center transition tracking-wide uppercase">
                                                                Approve Customs
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 bg-slate-900 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs font-mono text-slate-500 gap-2">
        <div>
            VORTEX CONTROL // DESIGNED FOR MULTI-Vessel OPERATORS. DUMMY FE_DATACAGE REQ.
        </div>
        <div class="flex gap-4">
            <a href="#" class="hover:text-slate-300 transition">TERMS OF TRANSIT</a>
            <span>•</span>
            <a href="#" class="hover:text-slate-300 transition">DOCUMENT SECURITY</a>
            <span>•</span>
            <a href="#" class="hover:text-slate-300 transition">API INTEGRATION (v12.2)</a>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Alpine.js Main JS Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Check list of exchange rates
            const EXCHANGE_RATES = {
                'EUR': 0.92,
                'IDR': 16345,
                'CNY': 7.24,
                'AUD': 1.49
            };

            const CURRENCY_SYMBOLS = {
                'EUR': '€',
                'IDR': 'Rp',
                'CNY': '¥',
                'AUD': 'A$'
            };

            window.dashboardApp = () => ({
                systemTime: '',
                selectedCargoId: '',
                calcValue: 100000,
                calcCurrency: 'EUR',
                map: null,
                routesLayer: null,
                markersLayer: null,
                
                // Smart filters state
                filters: {
                    continent: 'All',
                    mode: 'All',
                    risk: 'All'
                },

                // GNews Mock News Widget Data
                newsList: [
                    {
                        source: 'GNews API // Logistics Feed',
                        time: '10 MIN AGO',
                        title: 'Suez Canal Congestion Peaks as Rerouting Demands Increase',
                        summary: 'Major carrier groups announce route changes via Cape of Good Hope, causing a spike in global sea cargo container transit times.'
                    },
                    {
                        source: 'World Bank Trade News',
                        time: '2 HOURS AGO',
                        title: 'Logistics Performance Index Shows Infrastructure Gains in APAC',
                        summary: 'ASEAN ports see a 4% improvement in processing latency due to automation and upgraded container terminal tracking nodes.'
                    },
                    {
                        source: 'Open Meteo Alert',
                        time: '4 HOURS AGO',
                        title: 'Typhoon Approaching Shanghai Coastal Hub, Vessel Delays Warning',
                        summary: 'Open Meteo telemetry forecasts wind gusts up to 80 km/h at Port of Shanghai. Harbor operators issue red warning alerts.'
                    },
                    {
                        source: 'Marine Traffic Logs',
                        time: '6 HOURS AGO',
                        title: 'Rotterdam Port Terminal Faces Temporary Crane Maintenance Queue',
                        summary: 'Demurrage risk rises as offload queues increase at Terminal 4. Three bulk carriers currently rerouted to adjacent harbor docks.'
                    }
                ],

                // Master Cargo Shipment Logs (Dummy Data representing API parameter integrations)
                shipments: [
                    {
                        id: 'CRG-4098-CN',
                        mode: 'Sea',
                        origin: { name: 'Port of Shanghai', code: 'CN', lat: 31.23, lng: 121.47, continent: 'Asia' },
                        destination: { name: 'Port of Rotterdam', code: 'NL', lat: 51.92, lng: 4.47, continent: 'Europe' },
                        eta: '2026-07-10 14:00',
                        value: 1250000,
                        status: 'In-Transit',
                        risk: 'Low',
                        sparklineData: [4.1, 4.0, 4.2, 4.1, 3.9, 4.0, 4.1, 4.2, 4.0, 4.1], // Stable temp container (Pharma/Goods)
                        telemetry: {
                            carrier: 'MV CMA CGM Alexander',
                            mmsi: '228386000',
                            speed: 18.2,
                            heading: 265,
                            congestion: 3
                        },
                        weather: {
                            temp: 18.2,
                            wind: 12,
                            condition: 'Partly Cloudy // Safe',
                            time: 'UPDATED 10m AGO'
                        },
                        worldBank: {
                            gdp: '$1.15 Trillion',
                            lpi: '4.1',
                            lpiRank: '3',
                            riskIndex: 'Low'
                        },
                        documents: [
                            { name: 'Bill of Lading', ref: 'BL-SH-ROT-4098', status: 'Approved' },
                            { name: 'Certificate of Origin', ref: 'CO-CN-2908', status: 'Approved' },
                            { name: 'Commercial Invoice', ref: 'CI-998342', status: 'Approved' },
                            { name: 'Packing List', ref: 'PL-4098', status: 'Approved' },
                            { name: 'Customs Declaration', ref: 'CD-NL-8890', status: 'Approved' }
                        ]
                    },
                    {
                        id: 'CRG-9021-US',
                        mode: 'Sea',
                        origin: { name: 'Port of Rotterdam', code: 'NL', lat: 51.92, lng: 4.47, continent: 'Europe' },
                        destination: { name: 'Port of Newark', code: 'US', lat: 40.68, lng: -74.17, continent: 'America' },
                        eta: '2026-07-14 08:30',
                        value: 780000,
                        status: 'Port Congestion',
                        risk: 'Moderate',
                        sparklineData: [3.2, 3.5, 3.8, 4.1, 4.8, 5.2, 5.8, 6.3, 6.9, 7.2], // Rising temp container
                        telemetry: {
                            carrier: 'Ever Given',
                            mmsi: '353136000',
                            speed: 2.1,
                            heading: 18,
                            congestion: 8
                        },
                        weather: {
                            temp: 26.5,
                            wind: 24,
                            condition: 'Thunderstorms // Warning',
                            time: 'UPDATED 5m AGO'
                        },
                        worldBank: {
                            gdp: '$27.3 Trillion',
                            lpi: '3.8',
                            lpiRank: '14',
                            riskIndex: 'Moderate'
                        },
                        documents: [
                            { name: 'Bill of Lading', ref: 'BL-ROT-NWK-9021', status: 'Approved' },
                            { name: 'Certificate of Origin', ref: 'CO-NL-1102', status: 'Approved' },
                            { name: 'Commercial Invoice', ref: 'CI-112344', status: 'Warning' }, // Document check delay
                            { name: 'Packing List', ref: 'PL-9021', status: 'Approved' },
                            { name: 'Customs Declaration', ref: 'CD-US-3392', status: 'Pending' }
                        ]
                    },
                    {
                        id: 'CRG-1102-ID',
                        mode: 'Sea',
                        origin: { name: 'Tanjung Priok', code: 'ID', lat: -6.10, lng: 106.88, continent: 'Asia' },
                        destination: { name: 'Port of Los Angeles', code: 'US', lat: 33.74, lng: -118.26, continent: 'America' },
                        eta: '2026-07-22 17:15',
                        value: 2100000,
                        status: 'In-Transit',
                        risk: 'Low',
                        sparklineData: [22, 22, 23, 22.5, 22, 22, 22, 21.8, 22, 22], // Ambient Cargo
                        telemetry: {
                            carrier: 'Maersk Mc-Kinney Moller',
                            mmsi: '219403000',
                            speed: 14.5,
                            heading: 85,
                            congestion: 5
                        },
                        weather: {
                            temp: 22.1,
                            wind: 15,
                            condition: 'Clear Skies',
                            time: 'UPDATED 25m AGO'
                        },
                        worldBank: {
                            gdp: '$27.3 Trillion',
                            lpi: '3.8',
                            lpiRank: '14',
                            riskIndex: 'Low'
                        },
                        documents: [
                            { name: 'Bill of Lading', ref: 'BL-JKT-LA-1102', status: 'Approved' },
                            { name: 'Certificate of Origin', ref: 'CO-ID-00923', status: 'Approved' },
                            { name: 'Commercial Invoice', ref: 'CI-773412', status: 'Approved' },
                            { name: 'Packing List', ref: 'PL-1102', status: 'Approved' },
                            { name: 'Customs Declaration', ref: 'CD-US-9912', status: 'Approved' }
                        ]
                    },
                    {
                        id: 'CRG-7734-DE',
                        mode: 'Air',
                        origin: { name: 'Frankfurt Airport', code: 'DE', lat: 50.03, lng: 8.57, continent: 'Europe' },
                        destination: { name: 'Tokyo Haneda', code: 'JP', lat: 35.54, lng: 139.77, continent: 'Asia' },
                        eta: '2026-07-03 21:00',
                        value: 4500000,
                        status: 'Customs Cleared',
                        risk: 'Low',
                        sparklineData: [2.0, 2.0, 2.1, 2.0, 2.0, 2.0, 2.0, 2.0, 2.0, 2.0], // Super controlled pharma
                        telemetry: {
                            carrier: 'Polar Cargo B777F PO982',
                            mmsi: 'FL-PO982',
                            speed: 490,
                            heading: 42,
                            congestion: 2
                        },
                        weather: {
                            temp: 24.0,
                            wind: 8,
                            condition: 'Clear Sky // Night',
                            time: 'UPDATED 2m AGO'
                        },
                        worldBank: {
                            gdp: '$4.21 Trillion',
                            lpi: '3.9',
                            lpiRank: '9',
                            riskIndex: 'Low'
                        },
                        documents: [
                            { name: 'Bill of Lading', ref: 'AWB-FRA-HND-7734', status: 'Approved' },
                            { name: 'Certificate of Origin', ref: 'CO-DE-9923', status: 'Approved' },
                            { name: 'Commercial Invoice', ref: 'CI-119024', status: 'Approved' },
                            { name: 'Packing List', ref: 'PL-7734', status: 'Approved' },
                            { name: 'Customs Declaration', ref: 'CD-JP-23912', status: 'Approved' }
                        ]
                    },
                    {
                        id: 'CRG-3051-BR',
                        mode: 'Sea',
                        origin: { name: 'Port of Santos', code: 'BR', lat: -23.95, lng: -46.30, continent: 'America' },
                        destination: { name: 'Port of Hamburg', code: 'DE', lat: 53.55, lng: 9.99, continent: 'Europe' },
                        eta: '2026-07-18 11:45',
                        value: 920000,
                        status: 'Demurrage Risk',
                        risk: 'Critical',
                        sparklineData: [18.5, 19.2, 21.0, 23.4, 25.1, 27.2, 29.8, 31.0, 31.8, 32.5], // Heat damage anomaly
                        telemetry: {
                            carrier: 'MSC Oscar',
                            mmsi: '374821000',
                            speed: 16.8,
                            heading: 32,
                            congestion: 6
                        },
                        weather: {
                            temp: 14.5,
                            wind: 30,
                            condition: 'Heavy Rain // High Waves',
                            time: 'UPDATED 15m AGO'
                        },
                        worldBank: {
                            gdp: '$4.45 Trillion',
                            lpi: '4.3',
                            lpiRank: '1',
                            riskIndex: 'Low'
                        },
                        documents: [
                            { name: 'Bill of Lading', ref: 'BL-STS-HAM-3051', status: 'Warning' }, // Discrepancy spotted
                            { name: 'Certificate of Origin', ref: 'CO-BR-9908', status: 'Approved' },
                            { name: 'Commercial Invoice', ref: 'CI-305199', status: 'Warning' }, // Valuation query
                            { name: 'Packing List', ref: 'PL-3051', status: 'Approved' },
                            { name: 'Customs Declaration', ref: 'CD-DE-0091', status: 'Pending' } // Pending clearance
                        ]
                    },
                    {
                        id: 'CRG-2290-SG',
                        mode: 'Sea',
                        origin: { name: 'Port of Singapore', code: 'SG', lat: 1.26, lng: 103.82, continent: 'Asia' },
                        destination: { name: 'Port of Sydney', code: 'AU', lat: -33.86, lng: 151.21, continent: 'Oceania' },
                        eta: '2026-07-12 06:10',
                        value: 1650000,
                        status: 'In-Transit',
                        risk: 'Low',
                        sparklineData: [5.1, 5.0, 5.1, 5.2, 5.0, 5.1, 5.1, 5.0, 5.2, 5.1],
                        telemetry: {
                            carrier: 'ONE Apus',
                            mmsi: '351283000',
                            speed: 20.1,
                            heading: 142,
                            congestion: 4
                        },
                        weather: {
                            temp: 16.0,
                            wind: 10,
                            condition: 'Sunny // Clear',
                            time: 'UPDATED 30m AGO'
                        },
                        worldBank: {
                            gdp: '$1.72 Trillion',
                            lpi: '3.8',
                            lpiRank: '12',
                            riskIndex: 'Low'
                        },
                        documents: [
                            { name: 'Bill of Lading', ref: 'BL-SIN-SYD-2290', status: 'Approved' },
                            { name: 'Certificate of Origin', ref: 'CO-SG-8890', status: 'Approved' },
                            { name: 'Commercial Invoice', ref: 'CI-229011', status: 'Approved' },
                            { name: 'Packing List', ref: 'PL-2290', status: 'Approved' },
                            { name: 'Customs Declaration', ref: 'CD-AU-7762', status: 'Approved' }
                        ]
                    }
                ],

                // Init Leaflet & Alpine bindings
                init() {
                    // Update clock realtime
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);

                    // Initialize Map
                    this.$nextTick(() => {
                        this.initMap();
                    });
                },

                updateClock() {
                    const now = new Date();
                    const options = { 
                        year: 'numeric', 
                        month: 'short', 
                        day: '2-digit', 
                        hour: '2-digit', 
                        minute: '2-digit', 
                        second: '2-digit', 
                        hour12: false 
                    };
                    this.systemTime = now.toLocaleString('en-US', options).replace(/,/g, ' //');
                },

                // Initialize Leaflet Map with monochrome styles and custom vectors
                initMap() {
                    this.map = L.map('map', {
                        center: [20, 10],
                        zoom: 2,
                        minZoom: 1.5,
                        maxZoom: 8,
                        zoomControl: true,
                        attributionControl: false
                    });

                    // Add dark cartographic tiles to mimic "Industrial-Tech Clean" aesthetic
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        subdomains: 'abcd',
                        maxZoom: 20
                    }).addTo(this.map);

                    this.routesLayer = L.layerGroup().addTo(this.map);
                    this.markersLayer = L.layerGroup().addTo(this.map);

                    // Draw all shipments
                    this.drawMapFeatures();
                },

                // Filter logic
                filteredShipments() {
                    return this.shipments.filter(item => {
                        // Continent Destination filter
                        if (this.filters.continent !== 'All') {
                            if (this.filters.continent === 'America' && item.destination.continent !== 'America') return false;
                            if (this.filters.continent === 'Asia' && item.destination.continent !== 'Asia') return false;
                            if (this.filters.continent === 'Europe' && item.destination.continent !== 'Europe') return false;
                        }
                        // Transit Mode filter
                        if (this.filters.mode !== 'All' && item.mode !== this.filters.mode) return false;
                        // Risk Filter
                        if (this.filters.risk !== 'All' && item.risk !== this.filters.risk) return false;

                        return true;
                    });
                },

                resetFilters() {
                    this.filters.continent = 'All';
                    this.filters.mode = 'All';
                    this.filters.risk = 'All';
                    this.selectedCargoId = '';
                    this.drawMapFeatures();
                },

                activeAnomaliesCount() {
                    return this.shipments.filter(x => x.risk === 'Critical').length;
                },

                toggleSelectCargo(id) {
                    if (this.selectedCargoId === id) {
                        this.selectedCargoId = '';
                        this.map.setView([20, 10], 2);
                        this.drawMapFeatures();
                    } else {
                        this.selectedCargoId = id;
                        const cargo = this.shipments.find(x => x.id === id);
                        if (cargo) {
                            // Find cargo details and sync calculator values
                            this.calcValue = cargo.value;
                            
                            // Center map on path control midpoint
                            const midLat = (cargo.origin.lat + cargo.destination.lat) / 2;
                            const midLng = (cargo.origin.lng + cargo.destination.lng) / 2;
                            this.map.setView([midLat, midLng], 4);
                            
                            // Redraw map with focused styling
                            this.drawMapFeatures();
                        }
                    }
                },

                // Draws Bezier curved path vectors & port anchors on Leaflet
                drawMapFeatures() {
                    this.routesLayer.clearLayers();
                    this.markersLayer.clearLayers();

                    const shipments = this.filteredShipments();
                    
                    shipments.forEach(cargo => {
                        const isFocused = this.selectedCargoId === cargo.id;
                        const isAnySelected = this.selectedCargoId !== '';
                        const opacity = isAnySelected ? (isFocused ? 1.0 : 0.2) : 0.75;
                        const weight = isFocused ? 4 : 2;

                        // Calculate points for the curved polyline
                        const latlng1 = L.latLng(cargo.origin.lat, cargo.origin.lng);
                        const latlng2 = L.latLng(cargo.destination.lat, cargo.destination.lng);
                        const curvePoints = this.calculateCurvePoints(latlng1, latlng2);

                        // Color coding based on risk status
                        let routeColor = '#10B981'; // Green (Safe)
                        if (cargo.risk === 'Moderate') routeColor = '#F59E0B'; // Amber
                        if (cargo.risk === 'Critical') routeColor = '#EA580C'; // International Orange / Red

                        // Custom SVG classes for animated dash flow
                        const flowClass = cargo.risk === 'Critical' ? 'flow-path-fast' : 'flow-path';

                        // Draw curved polyline
                        const routePolyline = L.polyline(curvePoints, {
                            color: routeColor,
                            weight: weight,
                            opacity: opacity,
                            className: isFocused ? `${flowClass} stroke-orange-500` : flowClass
                        }).addTo(this.routesLayer);

                        // Add binding popup info on click
                        routePolyline.bindPopup(`
                            <div class="font-mono text-xs space-y-1">
                                <p class="text-orange-500 font-bold border-b border-slate-700 pb-0.5">${cargo.id}</p>
                                <p class="text-slate-300">Carrier: ${cargo.telemetry.carrier}</p>
                                <p class="text-slate-300">Route: ${cargo.origin.code} ➔ ${cargo.destination.code}</p>
                                <p class="text-slate-300">Status: <span class="font-semibold" style="color: ${routeColor}">${cargo.status}</span></p>
                            </div>
                        `);

                        // Draw custom icon markers for Origin & Destination ports
                        const originCircle = L.circleMarker(latlng1, {
                            radius: isFocused ? 7 : 5,
                            fillColor: '#475569',
                            color: '#94A3B8',
                            weight: 1.5,
                            fillOpacity: opacity
                        }).addTo(this.markersLayer);

                        originCircle.bindPopup(`<span class="font-mono text-xs text-slate-300 font-bold">Origin: ${cargo.origin.name} (${cargo.origin.code})</span>`);

                        // Destination Marker glows if focused or critical
                        const destCircle = L.circleMarker(latlng2, {
                            radius: isFocused ? 8 : 6,
                            fillColor: routeColor,
                            color: '#ffffff',
                            weight: isFocused ? 2 : 1,
                            fillOpacity: opacity
                        }).addTo(this.markersLayer);

                        destCircle.bindPopup(`
                            <div class="font-mono text-xs">
                                <p class="font-bold text-slate-200">Dest: ${cargo.destination.name}</p>
                                <p class="text-slate-400">Temp: ${cargo.weather.temp}°C | Wind: ${cargo.weather.wind}km/h</p>
                            </div>
                        `);

                        // Draw interactive pulsing dot for current location along the path
                        // We will simulate the vessel somewhere in the middle (e.g. 60% of the Bezier points)
                        if (cargo.status === 'In-Transit' || cargo.status === 'Port Congestion' || cargo.status === 'Demurrage Risk') {
                            const shipIndex = Math.floor(curvePoints.length * 0.65);
                            const shipPos = curvePoints[shipIndex];
                            
                            const pulseIcon = L.divIcon({
                                className: 'relative flex h-3 w-3',
                                html: `
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full" style="background-color: ${routeColor}; opacity: 0.6"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3" style="background-color: ${routeColor}; border: 1px solid white"></span>
                                `
                            });

                            const shipMarker = L.marker(shipPos, { 
                                icon: pulseIcon,
                                opacity: opacity
                            }).addTo(this.markersLayer);

                            shipMarker.bindPopup(`
                                <div class="font-mono text-xs">
                                    <p class="font-bold text-slate-200">${cargo.telemetry.carrier}</p>
                                    <p class="text-slate-400">Speed: ${cargo.telemetry.speed} kt | Heading: ${cargo.telemetry.heading}°</p>
                                </div>
                            `);
                        }
                    });
                },

                // Calculates a smooth quadratic bezier curve between two latlng nodes
                calculateCurvePoints(p1, p2, segments = 40) {
                    let points = [];
                    let midLat = (p1.lat + p2.lat) / 2;
                    let midLng = (p1.lng + p2.lng) / 2;
                    
                    // Add control point perpendicular offset to draw a nice curve
                    // Depending on longitude difference, we shift the curve upwards or downwards
                    let offsetLat = (p2.lng - p1.lng) * 0.12;
                    let offsetLng = (p1.lat - p2.lat) * 0.12;
                    
                    let control = L.latLng(midLat + offsetLat, midLng + offsetLng);
                    
                    for (let i = 0; i <= segments; i++) {
                        let t = i / segments;
                        // Quadratic Bezier interpolation formula
                        let lat = Math.pow(1 - t, 2) * p1.lat + 2 * (1 - t) * t * control.lat + Math.pow(t, 2) * p2.lat;
                        let lng = Math.pow(1 - t, 2) * p1.lng + 2 * (1 - t) * t * control.lng + Math.pow(t, 2) * p2.lng;
                        points.push([lat, lng]);
                    }
                    return points;
                },

                // Generate SVG Sparkline polyline coordinates
                generateSparklinePath(data) {
                    if (!data || data.length === 0) return '';
                    const min = Math.min(...data);
                    const max = Math.max(...data);
                    const range = max - min || 1;
                    
                    let points = [];
                    const step = 100 / (data.length - 1);
                    
                    data.forEach((val, idx) => {
                        const x = idx * step;
                        // Invert Y because SVG coordinates start from top-left
                        const y = 25 - ((val - min) / range) * 20; 
                        points.push(`${x},${y}`);
                    });
                    
                    return 'M ' + points.join(' L ');
                },

                // Helper to color code statuses
                statusBadgeColor(status) {
                    switch (status) {
                        case 'Customs Cleared':
                            return 'text-green-500 bg-green-500/10 border border-green-500/30';
                        case 'In-Transit':
                            return 'text-sky-500 bg-sky-500/10 border border-sky-500/30';
                        case 'Port Congestion':
                            return 'text-amber-500 bg-amber-500/10 border border-amber-500/30';
                        case 'Demurrage Risk':
                            return 'text-orange-500 bg-orange-500/10 border border-orange-500/30';
                        default:
                            return 'text-slate-400 bg-slate-800';
                    }
                },

                riskDotColor(risk) {
                    if (risk === 'Critical') return 'bg-orange-600';
                    if (risk === 'Moderate') return 'bg-amber-500';
                    return 'bg-green-500';
                },

                riskPulseColor(risk) {
                    if (risk === 'Critical') return 'bg-orange-600';
                    if (risk === 'Moderate') return 'bg-amber-500';
                    return 'bg-green-500';
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        maximumFractionDigits: 0
                    }).format(value);
                },

                formatConvertedValue(val, curr) {
                    const rate = EXCHANGE_RATES[curr] || 1;
                    const converted = val * rate;
                    const symbol = CURRENCY_SYMBOLS[curr] || '';
                    
                    return `${symbol} ${new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 }).format(converted)} (${curr})`;
                },

                // Mock functions for actions
                toggleDocStatus(cargoId, docName) {
                    const cargo = this.shipments.find(x => x.id === cargoId);
                    if (cargo) {
                        const doc = cargo.documents.find(d => d.name === docName);
                        if (doc) {
                            // Cycle through status for presentation purposes
                            if (doc.status === 'Pending') doc.status = 'Approved';
                            else if (doc.status === 'Warning') doc.status = 'Approved';
                            else doc.status = 'Pending';
                            
                            // Re-evaluate cargo risk rating based on documents
                            const warningDocs = cargo.documents.filter(d => d.status === 'Warning').length;
                            const pendingDocs = cargo.documents.filter(d => d.status === 'Pending').length;
                            
                            if (warningDocs > 0) cargo.risk = 'Critical';
                            else if (pendingDocs > 0) cargo.risk = 'Moderate';
                            else cargo.risk = 'Low';
                            
                            this.drawMapFeatures();
                        }
                    }
                },

                updateShipmentStatus(cargoId, newStatus) {
                    const cargo = this.shipments.find(x => x.id === cargoId);
                    if (cargo) {
                        cargo.status = newStatus;
                        // If customs cleared, documents get auto-approved
                        if (newStatus === 'Customs Cleared') {
                            cargo.documents.forEach(d => d.status = 'Approved');
                            cargo.risk = 'Low';
                        }
                        this.drawMapFeatures();
                        alert(`Cargo ${cargoId} updated to ${newStatus}. All checklist requirements successfully verified.`);
                    }
                },

                dispatchAlert(cargoId) {
                    alert(`ALERT DISPATCHED: Automated warning transited to carrier of cargo ${cargoId}. Operational logistics and port authority notified.`);
                }
            });
        });
    </script>
</body>
</html>
