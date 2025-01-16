<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->string('useragent')->nullable();
            $table->string('domain')->nullable();
            $table->string('type')->default('embed');
            $table->string('player')->default('mainPlayer');
            $table->boolean('hls')->default(0);
            // $table->boolean('embed')->default(0);
            // $table->boolean('p2p')->default(0);
            // $table->boolean('supportHost')->default(0);
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
        Schema::dropIfExists('servers');
    }
}
