<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cargo_id',
        'origin_port',
        'origin_city',
        'origin_country',
        'origin_country_code',
        'origin_lat',
        'origin_lng',
        'destination_port',
        'destination_city',
        'destination_country',
        'destination_country_code',
        'destination_lat',
        'destination_lng',
        'current_lat',
        'current_lng',
        'status',
        'risk_level',
        'transport_mode',
        'eta',
        'carrier_name',
        'vessel_name',
        'imo_number',
        'speed',
        'heading',
        'container_temp_history',
        'container_humidity_history',
        'document_bill_of_lading',
        'document_certificate_of_origin',
        'document_commercial_invoice',
        'document_packing_list',
        'document_customs_declaration',
        'value_usd',
        'currency',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'origin_lat' => 'decimal:6',
            'origin_lng' => 'decimal:6',
            'destination_lat' => 'decimal:6',
            'destination_lng' => 'decimal:6',
            'current_lat' => 'decimal:6',
            'current_lng' => 'decimal:6',
            'speed' => 'decimal:1',
            'heading' => 'integer',
            'eta' => 'datetime',
            'container_temp_history' => 'array',
            'container_humidity_history' => 'array',
            'value_usd' => 'decimal:2',
        ];
    }
}
