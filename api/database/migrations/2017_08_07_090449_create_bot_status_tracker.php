<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotStatusTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bot_status_tracker');
        Schema::create('bot_status_tracker', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('facebook_id');
            $table->unsignedInteger('status');
            $table->string("type", 100)->nullable();
            $table->string("payload", 100)->nullable();
            $table->string("category", 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}