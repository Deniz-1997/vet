<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceIdInUserSmsCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('model_user_sms_codes', function (Blueprint $table) {
            $table->integer('device_id')->nullable();
            $table->foreign('device_id')
                ->references('id')
                ->on('model_user_token_by_devices')
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
        Schema::table('model_user_sms_codes', function (Blueprint $table) {
            $table->dropColumn('device_id');
        });
    }
}
