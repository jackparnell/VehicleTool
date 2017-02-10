<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class VehicleCheck extends Eloquent {

	protected $table = 'vehicleCheck';

	protected $fillable = [
        'guid', 'unitNumber', 'driverFirstName', 'driverLastName', 'oil', 'water', 'lights', 'tyres', 'brakes'
    ];

    public $booleanColumns = ['oil', 'water', 'lights', 'tyres', 'brakes'];
	
	protected $guarded = ['id'];
	
}
