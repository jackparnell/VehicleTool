<?php

namespace App;

use App\Observers\ParameterObserver;
use Traits\Searchable;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Parameter extends Eloquent {
	use RevisionableTrait, Searchable;

	protected $table = 'parameters';

	public $fillable = array(
        'name',
        'value',
        'type'
    );
	
	protected $guarded = array(
        'id'
    );
	
	protected $dates = ['deleted_at'];

	public static function boot() {
		parent::boot();
	}

    public static function getValueFromName($name)
    {
        $parameter = self::where('name', $name)->first();

        if (is_object($parameter)) {
            return trim($parameter->value);
        } else {
            return '';
        }

    }

    public static function getIdFromName($name)
    {
        $parameter = self::where('name', $name)->first();

        if (is_object($parameter)) {
            return $parameter->id;
        } else {
            return '';
        }

    }

    public function emptyValuePermitted()
    {
        if ($this->type == 'textAllowEmpty') {
            return TRUE;
        }
        return FALSE;
    }

}
