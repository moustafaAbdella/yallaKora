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
        Schema::create('track_ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('app', ['player', 'main'])->default('main')->index();
            $table->json('app_info')->nullable();
            $table->json('ip_info')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->string('operating_system')->nullable();
            $table->integer('count_ads_success')->default(0);
            $table->integer('count_ads_failed')->default(0);
            $table->timestamp('last_success_ads_at')->nullable();
            $table->timestamp('last_failed_ads_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'app']);
            $table->unique(['device_id', 'app']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_ads');
    }
};
