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
        Schema::create('field_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider_type'); // RECYCLER, AGGREGATOR_HUB, PRO, INSPECTION_OFFICE
            $table->string('region');
            $table->string('district');
            $table->string('ward')->nullable();
            $table->string('phone');
            $table->text('accepted_materials'); // e.g. PET, HDPE, MULTILAYER
            $table->text('buyback_rates_json')->nullable(); // {"PET": 400, "HDPE": 350}
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_providers');
    }
};
