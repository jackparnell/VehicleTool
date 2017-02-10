<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDamageReportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('damageReport', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->string('guid')->unique();
            $table->string('unitNumber');
            $table->string('driverFirstName');
            $table->string('driverLastName');
            $table->string('damageLocation');
            $table->text('damageDescription');
            $table->string('damageDriverSignature');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('damageReport');
	}

}
