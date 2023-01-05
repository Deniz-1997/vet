<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelFileListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file.model_file_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_user_id')->index();

            $table->text('path');
            $table->string('extension')->index();
            $table->string('type', 50)->index();
            $table->string('md5', 133)->index()->unique();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file.model_file_lists');
    }
}
