<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelNotificationsPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications.model_notifications_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('notification_id');
            $table->integer('created_user_id');
            $table->boolean('allow_send');
            $table->timestamps();

            $table->index('notification_id');
            $table->index('created_user_id');

            $table->foreign('notification_id')
                ->references('id')
                ->on('notifications.model_notifications_sends')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications.model_notifications_permissions');
    }
}
