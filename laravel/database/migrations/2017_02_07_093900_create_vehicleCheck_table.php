<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleCheckTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicleCheck', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->string('guid')->unique();
            $table->string('unitNumber');
            $table->string('driverFirstName');
            $table->string('driverLastName');
            $table->boolean('oil');
            $table->boolean('water');
            $table->boolean('lights');
            $table->boolean('tyres');
            $table->boolean('brakes');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicleCheck');
	}

}
