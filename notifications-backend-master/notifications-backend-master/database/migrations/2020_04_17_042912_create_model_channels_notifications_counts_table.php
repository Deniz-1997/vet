<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelChannelsNotificationsCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels.model_channels_notifications_counts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('notification_id');
            $table->integer('user_id');

            $table->integer('count')->nullable()->default(0);


            $table->index('user_id');
            $table->index('channel_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT');

            $table->foreign('notification_id')
                ->references('id')
                ->on('notifications.model_notifications_lists')
                ->onDelete('RESTRICT');

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
        Schema::dropIfExists('channels.model_channels_notifications_counts');
    }
}
