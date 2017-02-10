<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class DamageReport extends Eloquent {

	protected $table = 'damageReport';

    protected $fillable = [
        'guid', 'unitNumber', 'driverFirstName', 'driverLastName', 'damageLocation', 'damageDescription', 'damageDriverSignature'
    ];

	protected $guarded = ['id'];
	
}
