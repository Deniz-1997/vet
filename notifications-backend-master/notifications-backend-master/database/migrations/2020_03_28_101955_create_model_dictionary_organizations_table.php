<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelDictionaryOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary.model_dictionary_organizations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');

            $table->integer('parent_id')->nullable();

            $table->index('parent_id');

            $table->foreign('parent_id')
                ->references('id')
                ->on('dictionary.model_dictionary_organizations')
                ->onDelete('RESTRICT');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dictionary.model_dictionary_organizations');
    }
}
