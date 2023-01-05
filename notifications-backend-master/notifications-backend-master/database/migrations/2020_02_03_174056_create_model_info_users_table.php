<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelInfoUsersTable extends Migration
{
    /**
     *
     * @return void
     * @deprecated
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::create('model_info_users', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('structure_id')->nullable();
            $table->integer('substructure_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->string('phone');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT');

            $table->foreign('structure_id')
                ->references('id')
                ->on('dictionary.model_dictionary_structures')
                ->onDelete('RESTRICT');

            $table->foreign('substructure_id')
                ->references('id')
                ->on('dictionary.model_dictionary_substructures')
                ->onDelete('RESTRICT');

            $table->foreign('group_id')
                ->references('id')
                ->on('dictionary.model_dictionary_group_users')
                ->onDelete('RESTRICT');

            $table->index(['user_id']);
            $table->index(['structure_id', 'substructure_id']);
            $table->index(['group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_info_users');
    }
}
