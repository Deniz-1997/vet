<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelNotificationsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications.model_notifications_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('text')->nullable();
            $table->integer('notifications_id');
            $table->integer('event_id');
            $table->softDeletes();
            $table->timestamps();

            $table->index('event_id');
            $table->index('notifications_id');

            $table->foreign('notifications_id')
                ->references('id')
                ->on('notifications.model_notifications_lists')
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
        Schema::dropIfExists('model_notifications_events');
    }
}
