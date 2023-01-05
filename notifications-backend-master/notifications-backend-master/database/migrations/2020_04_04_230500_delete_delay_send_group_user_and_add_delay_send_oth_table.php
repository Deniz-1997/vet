<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteDelaySendGroupUserAndAddDelaySendOthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dictionary.model_dictionary_group_users', function (Blueprint $table) {
            $table->boolean('responsible_notifications')->nullable()->default(false);
            $table->boolean('filter_notifications')->nullable()->default(false);
            $table->dropColumn('delay_send');
        });

        Schema::table('templates.model_templates_group_users', function (Blueprint $table) {
            $table->integer('delay_send')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionary.model_dictionary_group_users', function (Blueprint $table) {
            $table->integer('delay_send')->nullable();
            $table->dropColumn('responsible_notifications');
            $table->dropColumn('filter_notifications');
        });

        Schema::table('templates.model_templates_group_users', function (Blueprint $table) {
            $table->dropColumn('delay_send');
        });
    }
}
