<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefectReportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('defectReport', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->string('guid')->unique();
            $table->string('unitNumber');
            $table->string('driverFirstName');
            $table->string('driverLastName');
            $table->string('defectCategory');
            $table->text('defectDescription');
            $table->string('driverSignature');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('defectReport');
	}

}
