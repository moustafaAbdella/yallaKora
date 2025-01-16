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
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscription_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('payment_id')->unsigned()->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('type');
            $table->integer('duration')->nullable();
            $table->string('currency')->nullable();
            $table->integer('price')->nullable();        
            $table->boolean('active')->default(1);
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('subscription_histories');
    }
};
