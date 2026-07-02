<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('cargo_id')->unique();
            $table->string('origin_port');
            $table->string('origin_city');
            $table->string('origin_country');
            $table->string('origin_country_code');
            $table->decimal('origin_lat', 10, 6);
            $table->decimal('origin_lng', 10, 6);
            $table->string('destination_port');
            $table->string('destination_city');
            $table->string('destination_country');
            $table->string('destination_country_code');
            $table->decimal('destination_lat', 10, 6);
            $table->decimal('destination_lng', 10, 6);
            $table->decimal('current_lat', 10, 6);
            $table->decimal('current_lng', 10, 6);
            $table->string('status'); // In-Transit, Customs Cleared, Port Congestion, Demurrage Risk
            $table->string('risk_level'); // Low, Medium, High
            $table->string('transport_mode'); // Sea, Air, Land
            $table->dateTime('eta');
            $table->string('carrier_name');
            $table->string('vessel_name')->nullable();
            $table->string('imo_number')->nullable();
            $table->decimal('speed', 4, 1)->default(0.0);
            $table->integer('heading')->default(0);
            $table->json('container_temp_history');
            $table->json('container_humidity_history');
            $table->string('document_bill_of_lading')->default('Pending');
            $table->string('document_certificate_of_origin')->default('Pending');
            $table->string('document_commercial_invoice')->default('Pending');
            $table->string('document_packing_list')->default('Pending');
            $table->string('document_customs_declaration')->default('Pending');
            $table->decimal('value_usd', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
