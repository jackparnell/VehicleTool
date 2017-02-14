<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taskLog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->datetime('start')->nullable();
            $table->datetime('end')->nullable();
            $table->string('sapi');
            $table->string('username')->nullable();
            $table->boolean('exception')->default(0);
            $table->text('exceptionInfo')->nullable();
            $table->integer('runTimeSeconds')->nullable();
            $table->integer('maximumRunTimeExpectationSeconds')->nullable();
            $table->integer('runTimeFlag')->default(0);
            $table->boolean('runTimeFlagNotificationSent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('taskLog');
    }
}
