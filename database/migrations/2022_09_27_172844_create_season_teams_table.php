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
        Schema::create('season_teams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('league_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();
            $table->bigInteger('season_id')->unsigned();
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
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
        Schema::dropIfExists('season_teams');
    }
};
