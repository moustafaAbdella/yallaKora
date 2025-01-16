<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->timestamp('last_activity')->index();
            $table->string('ip', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('device')->nullable()->index();
            $table->string('operating_system')->nullable()->index();
            $table->string('country_code')->nullable()->index();
            $table->string('country_name')->nullable()->index();
            $table->boolean('blocked')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
};
