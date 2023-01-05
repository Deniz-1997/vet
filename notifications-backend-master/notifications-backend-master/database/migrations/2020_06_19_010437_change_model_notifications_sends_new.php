<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeModelNotificationsSendsNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications.model_notifications_sends', function (Blueprint $table) {
            $table->boolean('allowed_to_send')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications.model_notifications_sends', function (Blueprint $table) {
            $table->dropColumn('allowed_to_send');
        });
    }
}
