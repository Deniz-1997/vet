<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelChannelsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels.model_channels_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_id');
            $table->integer('channel_id');
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels.model_channels')
                ->onDelete('RESTRICT');

            $table->foreign('event_id')
                ->references('id')
                ->on('events.model_events_lists')
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
        Schema::dropIfExists('channels.model_channels_events');
    }
}
