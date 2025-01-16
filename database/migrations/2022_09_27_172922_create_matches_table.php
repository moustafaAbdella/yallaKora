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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('api_id')->unsigned()->unique()->nullable();
            $table->integer('team_re_a')->default(0);
            $table->integer('team_re_b')->default(0);
            $table->integer('seasonNum')->nullable();
            $table->integer('stageNum')->nullable();
            $table->integer('roundNum')->nullable();
            $table->string('competitionDisplayName')->nullable();
            $table->timestamp('startTime')->useCurrent();;
            $table->integer('statusGroup')->default(0);
            $table->string('statusText')->default(0);
            $table->string('shortStatusText')->default(0);
            $table->integer('gameTimeAndStatusDisplayType')->default(0);
            //active
            $table->boolean('active')->default(1);
            $table->boolean('justEnded')->default(0);
            $table->integer('gameTime')->default(0);
            $table->string('gameTimeDisplay')->nullable();
            $table->integer('winner')->default(0);
            $table->string('teamWinner')->default('a');
            $table->boolean('is_live')->default(0);
            $table->boolean('hasLineups')->default(0);
            $table->boolean('hasMissingPlayers')->default(0);
            $table->boolean('hasFieldPositions')->default(0);
            $table->boolean('hasStats')->default(0);
            $table->boolean('hasStandings')->default(0);
            $table->boolean('hasBrackets')->default(0);
            $table->boolean('hasPreviousMeetings')->default(0);
            $table->boolean('hasRecentMatches')->default(0);
            $table->boolean('hasBets')->default(0);
            $table->boolean('hasPlayerBets')->default(0);
            $table->foreignId('com_id')->constrained('leagues')->cascadeOnDelete();
            $table->foreignId('team_id_a')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('team_id_b')->constrained('teams')->cascadeOnDelete();
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
        Schema::dropIfExists('matches');
    }
};
