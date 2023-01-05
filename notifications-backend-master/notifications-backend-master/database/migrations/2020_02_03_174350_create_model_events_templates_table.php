<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelEventsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events.model_events_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('template_id');
            $table->integer('event_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('template_id');
            $table->index('event_id');

            $table->foreign('template_id')
                ->references('id')
                ->on('templates.model_templates_lists')
                ->onDelete('RESTRICT');

            $table->foreign('event_id')
                ->references('id')
                ->on('events.model_events_lists')
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
        Schema::dropIfExists('events.model_events_templates');
    }
}
