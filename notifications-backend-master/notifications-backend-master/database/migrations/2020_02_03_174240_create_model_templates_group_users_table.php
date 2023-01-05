<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelTemplatesGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates.model_templates_group_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('group_id');
            $table->integer('template_id');
            $table->smallInteger('priority')->default(1);
            $table->timestamps();

            $table->index('group_id');
            $table->index('template_id');

            $table->foreign('template_id')
                ->references('id')
                ->on('templates.model_templates_lists')
                ->onDelete('RESTRICT');

            $table->foreign('group_id')
                ->references('id')
                ->on('dictionary.model_dictionary_group_users')
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
        Schema::dropIfExists('templates.model_templates_group_users');
    }
}
