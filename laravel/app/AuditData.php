<?php

namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Venturecraft\Revisionable\RevisionableTrait;

class AuditData extends Eloquent {
	use RevisionableTrait;

	protected $table = 'auditData';

	protected $fillable = array(
        'model',
        'rowId',
        'action',
        'user',
        'moment',
        'snapshot'
    );
	
	protected $guarded = array('id');
	
}
