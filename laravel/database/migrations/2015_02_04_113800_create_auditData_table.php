<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('auditData', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('model');
			$table->integer('rowId');
			$table->string('action');
			$table->string('user');
			$table->dateTime('moment');
			$table->text('snapshot');
				
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('auditData');
	}

}
