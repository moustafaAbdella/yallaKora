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
        Schema::create('matche_videos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('matche_id')->unsigned();
            $table->string('server');
            $table->string('link');
            $table->boolean('status')->default(1);
            $table->foreign('matche_id')->references('id')->on('matches')->onDelete('cascade');
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
        Schema::dropIfExists('matche_videos');
    }
};
