<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelUserSmsCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_user_sms_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->smallInteger('code');
            $table->text('hash');
            $table->boolean('completed')->nullable()->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('code');
            $table->index('hash');
            $table->index('completed');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('model_user_sms_codes');
    }
}
