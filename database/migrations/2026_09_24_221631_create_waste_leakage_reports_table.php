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
        Schema::create('waste_leakage_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code', 20)->unique();
            $table->string('sender_phone');
            $table->string('district');
            $table->text('location_details');
            $table->text('description');
            $table->string('status', 50)->default('PENDING'); // PENDING, ASSIGNED, RESOLVED
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_leakage_reports');
    }
};
