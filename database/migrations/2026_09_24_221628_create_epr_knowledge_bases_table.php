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
        Schema::create('epr_knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // REGULATION, ECO_DESIGN, FEE_TIERS, RECYCLING_GUIDE
            $table->text('content');
            $table->text('keywords');
            $table->string('language', 10)->default('sw');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epr_knowledge_bases');
    }
};
