<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelNotificationsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications.model_notifications_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('channel_id');
            $table->string('name');
            $table->string('status');
            $table->softDeletes();
            $table->timestamps();

            $table->index('channel_id');
            $table->index('name');

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
        Schema::dropIfExists('notifications.model_notifications_lists');
    }
}
