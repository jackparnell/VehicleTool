<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Permission extends Eloquent {

	protected $table = 'permissions';

	protected $fillable = array('groupName', 'resource', 'create', 'review', 'update', 'delete');
	
	protected $guarded = array('id');
	
}
