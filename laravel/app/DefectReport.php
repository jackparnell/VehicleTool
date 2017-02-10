<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class DefectReport extends Eloquent {

	protected $table = 'defectReport';

    protected $fillable = [
        'guid', 'unitNumber', 'driverFirstName', 'driverLastName', 'defectCategory', 'defectDescription', 'driverSignature'
    ];

	protected $guarded = ['id'];
	
}
