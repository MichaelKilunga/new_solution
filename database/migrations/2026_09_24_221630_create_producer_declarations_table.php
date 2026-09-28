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
        Schema::create('producer_declarations', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('tin_number');
            $table->string('packaging_type');
            $table->string('material_category');
            $table->decimal('quarterly_tonnage', 10, 2);
            $table->decimal('calculated_eco_fee', 12, 2);
            $table->string('pro_membership_id')->nullable();
            $table->string('status', 50)->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producer_declarations');
    }
};
