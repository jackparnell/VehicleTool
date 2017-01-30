<?php
namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Group extends Eloquent {

	protected $table = 'groups';

	protected $fillable = array('name');
	
	protected $guarded = array('id');
	
}
