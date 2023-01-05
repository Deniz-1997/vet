<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelChannelsApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels.model_channels_apis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('channel_id');
            $table->text('api_token');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->index('channel_id');
            $table->index('api_token');

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels.model_channels')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels.model_channels_apis');
    }
}
