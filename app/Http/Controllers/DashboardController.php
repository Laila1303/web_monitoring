<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Global Supply Chain Risk Intelligence Platform.
     */
    public function index(Request $request): View
    {
        // Countries data matching the PDF case studies and metrics
        $countries = [
            'DE' => [
                'name' => 'Germany',
                'code' => 'DE',
                'currency' => 'EUR',
                'population' => '83.2 Million',
                'gdp' => '4.07 Trillion USD',
                'inflation' => '2.2%',
                'base_inflation_rate' => 2.2,
                'gdp_trend' => [3.8, 3.9, 4.0, 4.07],
                'inflation_trend' => [3.1, 2.8, 2.5, 2.2],
                'currency_trend' => [1.05, 1.07, 1.08, 1.09], // EUR to USD conversion trend
                'weather_current' => 'Light Rain & Clouds',
                'weather_risk' => 5, // 5%
                'inflation_risk' => 10, // 10%
                'currency_risk' => 4, // 4%
                'news_sentiment_risk' => 3, // 3%
                'total_risk' => 22,
                'weather_conditions' => ['Rain', 'Strong Wind'],
            ],
            'CN' => [
                'name' => 'China',
                'code' => 'CN',
                'currency' => 'CNY',
                'population' => '1.41 Billion',
                'gdp' => '17.96 Trillion USD',
                'inflation' => '0.8%',
                'base_inflation_rate' => 0.8,
                'gdp_trend' => [16.8, 17.2, 17.5, 17.96],
                'inflation_trend' => [1.5, 1.2, 1.0, 0.8],
                'currency_trend' => [6.9, 7.1, 7.2, 7.25], // CNY per USD
                'weather_current' => 'Severe Typhoon Warning',
                'weather_risk' => 12,
                'inflation_risk' => 5,
                'currency_risk' => 15,
                'news_sentiment_risk' => 15,
                'total_risk' => 47,
                'weather_conditions' => ['Storm', 'Strong Wind'],
            ],
            'ID' => [
                'name' => 'Indonesia',
                'code' => 'ID',
                'currency' => 'IDR',
                'population' => '273.5 Million',
                'gdp' => '1.32 Trillion USD',
                'inflation' => '2.8%',
                'base_inflation_rate' => 2.8,
                'gdp_trend' => [1.18, 1.25, 1.29, 1.32],
                'inflation_trend' => [4.2, 3.8, 3.2, 2.8],
                'currency_trend' => [15200, 15600, 16100, 16350], // IDR per USD
                'weather_current' => 'Heavy Monsoon Rain',
                'weather_risk' => 8,
                'inflation_risk' => 15,
                'currency_risk' => 8,
                'news_sentiment_risk' => 4,
                'total_risk' => 35,
                'weather_conditions' => ['Rain', 'Strong Wind'],
            ],
            'AU' => [
                'name' => 'Australia',
                'code' => 'AU',
                'currency' => 'AUD',
                'population' => '26.0 Million',
                'gdp' => '1.68 Trillion USD',
                'inflation' => '3.6%',
                'base_inflation_rate' => 3.6,
                'gdp_trend' => [1.55, 1.60, 1.63, 1.68],
                'inflation_trend' => [5.1, 4.5, 4.0, 3.6],
                'currency_trend' => [0.64, 0.65, 0.66, 0.67], // AUD to USD
                'weather_current' => 'Clear & Gale Winds',
                'weather_risk' => 4,
                'inflation_risk' => 18,
                'currency_risk' => 5,
                'news_sentiment_risk' => 2,
                'total_risk' => 29,
                'weather_conditions' => ['Strong Wind'],
            ],
            'US' => [
                'name' => 'United States',
                'code' => 'US',
                'currency' => 'USD',
                'population' => '331.9 Million',
                'gdp' => '25.46 Trillion USD',
                'inflation' => '3.1%',
                'base_inflation_rate' => 3.1,
                'gdp_trend' => [23.5, 24.2, 24.8, 25.46],
                'inflation_trend' => [6.5, 4.9, 3.7, 3.1],
                'currency_trend' => [1.0, 1.0, 1.0, 1.0],
                'weather_current' => 'Clear Sky',
                'weather_risk' => 6,
                'inflation_risk' => 14,
                'currency_risk' => 3,
                'news_sentiment_risk' => 5,
                'total_risk' => 28,
                'weather_conditions' => ['Clear'],
            ],
        ];

        // Ports dataset matching the "World Port Index" parameter (Port Location Dashboard)
        $ports = [
            [
                'name' => 'Port of Hamburg',
                'code' => 'DE-HAM',
                'country_code' => 'DE',
                'lat' => 53.5511,
                'lng' => 9.9937,
                'cargo_throughput' => '8.7M TEU',
                'queue_time' => '1.5 Days',
            ],
            [
                'name' => 'Port of Wilhelmshaven',
                'code' => 'DE-WVN',
                'country_code' => 'DE',
                'lat' => 53.5222,
                'lng' => 8.1481,
                'cargo_throughput' => '1.2M TEU',
                'queue_time' => '0.5 Days',
            ],
            [
                'name' => 'Port of Shenzhen',
                'code' => 'CN-SZX',
                'country_code' => 'CN',
                'lat' => 22.4833,
                'lng' => 113.8833,
                'cargo_throughput' => '30.0M TEU',
                'queue_time' => '3.8 Days',
            ],
            [
                'name' => 'Port of Shanghai',
                'code' => 'CN-SHA',
                'country_code' => 'CN',
                'lat' => 31.2222,
                'lng' => 121.4581,
                'cargo_throughput' => '47.3M TEU',
                'queue_time' => '2.1 Days',
            ],
            [
                'name' => 'Port of Tanjung Priok',
                'code' => 'ID-TPP',
                'country_code' => 'ID',
                'lat' => -6.1030,
                'lng' => 106.8833,
                'cargo_throughput' => '7.8M TEU',
                'queue_time' => '1.8 Days',
            ],
            [
                'name' => 'Port of Tanjung Perak',
                'code' => 'ID-SUB',
                'country_code' => 'ID',
                'lat' => -7.2025,
                'lng' => 112.7297,
                'cargo_throughput' => '3.9M TEU',
                'queue_time' => '1.1 Days',
            ],
            [
                'name' => 'Port of Sydney',
                'code' => 'AU-SYD',
                'country_code' => 'AU',
                'lat' => -33.8688,
                'lng' => 151.2093,
                'cargo_throughput' => '2.6M TEU',
                'queue_time' => '0.8 Days',
            ],
            [
                'name' => 'Port of Melbourne',
                'code' => 'AU-MEL',
                'country_code' => 'AU',
                'lat' => -37.8136,
                'lng' => 144.9631,
                'cargo_throughput' => '3.2M TEU',
                'queue_time' => '1.2 Days',
            ],
            [
                'name' => 'Port of Los Angeles',
                'code' => 'US-LAX',
                'country_code' => 'US',
                'lat' => 33.7438,
                'lng' => -118.2673,
                'cargo_throughput' => '10.6M TEU',
                'queue_time' => '4.2 Days',
            ],
        ];

        // Logistics/Trade News containing sentiment keywords to run Lexicon-based analysis (News Intelligence)
        $news = [
            [
                'title' => 'Global trade growth and increase in exports observed this quarter',
                'category' => 'Trade',
                'snippet' => 'Recent logs indicate a steady growth and profit increase across European and Asian supply corridors, showing stable progress.',
                'date' => '2 hours ago',
                'country_code' => 'DE',
            ],
            [
                'title' => 'Hamburg port reports labor delay and inflation crisis',
                'category' => 'Logistics',
                'snippet' => 'Port workers protest rising inflation, causing minor delay in container loading. However, general operations remain stable.',
                'date' => '5 hours ago',
                'country_code' => 'DE',
            ],
            [
                'title' => 'Typhoon disaster brings severe delay to Shenzhen and Shanghai ports',
                'category' => 'Shipping',
                'snippet' => 'A major storm warning has hit mainland China coasts. Port congestion peaks as shipping operations face extreme delay due to weather disaster.',
                'date' => '1 hour ago',
                'country_code' => 'CN',
            ],
            [
                'title' => 'Suez Canal tensions increase geopolitical risk and inflation pressure',
                'category' => 'Economy',
                'snippet' => 'Rerouting vessels around Africa triggers higher transit fuel costs, pushing up inflation index and causing freight delay risks.',
                'date' => '1 day ago',
                'country_code' => 'CN',
            ],
            [
                'title' => 'Tanjung Priok customs upgrade improves clearance time and logistics profit',
                'category' => 'Logistics',
                'snippet' => 'The implementation of new digital tracking systems brings stable cargo clearance, improving general export growth.',
                'date' => '6 hours ago',
                'country_code' => 'ID',
            ],
            [
                'title' => 'Rupiah currency drop causes import inflation concern',
                'category' => 'Economy',
                'snippet' => 'Economic crisis concerns rise as fuel import costs increase, adding domestic inflation pressure and supply chain delay.',
                'date' => '12 hours ago',
                'country_code' => 'ID',
            ],
            [
                'title' => 'Australia mining exports see growth despite shipping delay',
                'category' => 'Trade',
                'snippet' => 'Iron ore shipments improve due to high demand, offsetting minor port delay and weather concerns.',
                'date' => '3 hours ago',
                'country_code' => 'AU',
            ],
        ];

        return view('dashboard', compact('countries', 'ports', 'news'));
    }
}
