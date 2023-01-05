<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelNotificationsSendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications.model_notifications_sends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('notify_event_id');

            $table->boolean('send_email')->default(false)->nullable();
            $table->boolean('send_sms')->default(false)->nullable();
            $table->boolean('send_device')->default(false)->nullable();
            $table->boolean('send')->default(false)->nullable();
            $table->boolean('viewed')->default(false)->nullable();

            $table->timestamp('sended_date');
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
            $table->index('notify_event_id');
            $table->index('viewed');
            $table->index('send');

            $table->foreign('notify_event_id')->references('id')
                ->on('notifications.model_notifications_events')->onDelete('RESTRICT');

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications.model_notifications_sends');
    }
}
