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
        Schema::create('track_ads_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('track_ad_id')->nullable()->constrained('track_ads')->onDelete('cascade');
            $table->string('provider')->nullable(); //admob or unit
            $table->string('ad_type')->nullable(); // banner
            $table->boolean('ad_shown')->nullable();
            $table->json('app_info')->nullable();
            $table->json('ip_info')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->string('operating_system')->nullable();
            $table->timestamp('shown_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_ads_details');
    }
};
