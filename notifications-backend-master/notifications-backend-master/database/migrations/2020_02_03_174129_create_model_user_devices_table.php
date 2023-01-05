<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_user_devices', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('device_id');
            $table->enum('device', ['ios', 'android']);
            $table->text('token')->nullable();
            $table->text('pass')->nullable();
            $table->text('reg_id')->nullable();
            $table->text('access_key')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('device_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT');

            $table->foreign('device_id')
                ->references('id')
                ->on('model_user_devices')
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
        Schema::dropIfExists('model_user_devices');
    }
}
