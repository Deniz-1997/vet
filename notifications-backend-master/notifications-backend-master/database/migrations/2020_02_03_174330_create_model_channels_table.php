<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels.model_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sms_limit');
            $table->integer('email_limit');
            $table->string('name');

            $table->boolean('send_sms');
            $table->boolean('send_email');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels.model_channels');
    }
}
