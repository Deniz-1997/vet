<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelEventsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events.model_events_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('file_id');
            $table->integer('main_organization_id');
            $table->string('name');
            $table->boolean('hierarchy');
            $table->timestamps();
            $table->softDeletes();

            $table->index('file_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events.model_events_lists');
    }
}
