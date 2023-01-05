<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelUserNotificationOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_user_notification_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('device_id');
            $table->enum('type', ['alwaysEnabled', 'alwaysDisabled', 'scheduled']);
            $table->string('from_time')->nullable()->default('00:00');
            $table->string('to_time')->nullable()->default('00:00');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('type');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->foreign('device_id')
                ->references('id')
                ->on('model_user_devices')
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
        Schema::dropIfExists('model_user_notification_options');
    }
}
