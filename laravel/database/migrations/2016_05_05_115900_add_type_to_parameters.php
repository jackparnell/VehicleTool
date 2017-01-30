<?php

use Illuminate\Database\Migrations\Migration;

class AddTypeToParameters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parameters', function($table)
		{

            $table->string('type')->nullable();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('parameters', function($table)
		{
            $table->dropColumn(array('type'));
		});
	}

}
