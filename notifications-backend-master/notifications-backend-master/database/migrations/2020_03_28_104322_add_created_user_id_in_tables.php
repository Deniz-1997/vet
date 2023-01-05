<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedUserIdInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events.model_events_lists', function (Blueprint $table) {
            $table->integer('main_organization_id')->nullable();

            $table->foreign('main_organization_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->index('main_organization_id');
        });

        Schema::table('events.model_events_templates', function (Blueprint $table) {
            $table->integer('main_organization_id')->nullable();

            $table->foreign('main_organization_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->index('main_organization_id');
        });

        Schema::table('templates.model_templates_lists', function (Blueprint $table) {
            $table->integer('main_organization_id')->nullable();

            $table->foreign('main_organization_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->index('main_organization_id');
        });

        Schema::table('templates.model_templates_group_users', function (Blueprint $table) {
            $table->integer('main_organization_id')->nullable();

            $table->foreign('main_organization_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->index('main_organization_id');
        });

        Schema::table('dictionary.model_dictionary_group_users', function (Blueprint $table) {
            $table->integer('main_organization_id')->nullable();

            $table->foreign('main_organization_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->index('main_organization_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('main_organization_id')->nullable();

            $table->foreign('main_organization_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->index('main_organization_id');
        });

        Schema::table('model_user_groups', function (Blueprint $table) {
            $table->integer('main_organization_id')->nullable();

            $table->foreign('main_organization_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->index('main_organization_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events.model_events_lists', function (Blueprint $table) {
            $table->dropColumn('main_organization_id');
        });

        Schema::table('events.model_events_templates', function (Blueprint $table) {
            $table->dropColumn('main_organization_id');
        });

        Schema::table('templates.model_templates_lists', function (Blueprint $table) {
            $table->dropColumn('main_organization_id');
        });

        Schema::table('templates.model_templates_group_users', function (Blueprint $table) {
            $table->dropColumn('main_organization_id');
        });

        Schema::table('dictionary.model_dictionary_group_users', function (Blueprint $table) {
            $table->dropColumn('main_organization_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('main_organization_id');
        });

        Schema::table('model_user_groups', function (Blueprint $table) {
            $table->dropColumn('main_organization_id');
        });
    }
}
