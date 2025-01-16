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
        Schema::create('leagues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('api_id')->unsigned()->unique()->nullable();;
            $table->integer('currentSeasonNum')->nullable();
            $table->integer('currentStageNum')->nullable();
            $table->boolean('featured')->default(0);
            $table->integer('position')->nullable();
            $table->integer('popularityRank')->nullable();
            $table->string('api_slug');
            $table->string('color')->nullable();
            $table->string('type')->nullable();
            $table->string('country_name')->nullable();
            $table->integer('competitorsType')->nullable();
            $table->string('name');
            $table->string('name_ar')->nullable();
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
        Schema::dropIfExists('leagues');
    }
};
