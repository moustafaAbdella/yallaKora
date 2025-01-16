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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('api_id')->unsigned()->unique()->nullable();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('country')->nullable();
            $table->string('country_ar')->nullable();
            $table->string('country_iso2')->nullable();
            $table->string('venue')->nullable();
            $table->string('venue_ar')->nullable();
            $table->string('color')->nullable();
            $table->boolean('featured')->default(0);
            $table->integer('position')->nullable();
            $table->string('longName')->nullable();
            $table->integer('mainCompetitionId')->nullable();
            $table->integer('competitorNum')->nullable();
            $table->string('nameForURL')->nullable();
            $table->integer('popularityRank')->nullable();
            $table->string('symbolicName')->nullable();
            $table->integer('type')->nullable();
            $table->string('img')->nullable();
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
        Schema::dropIfExists('teams');
    }
};
