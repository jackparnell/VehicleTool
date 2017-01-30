<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('groupName');
			$table->string('resource');
			$table->boolean('create')->default(false);
			$table->boolean('review')->default(false);
			$table->boolean('update')->default(false);
			$table->boolean('delete')->default(false);
				
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permissions');
	}

}
