<?php

class SourceType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sourceTypes';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');

	/**
	 * Enable timestamps on data
	 *
	 * @var array
	 */
	public $timestamps = false;

	/**
	* Accesses calendars
	*/
	public function calendars()
    {
        return $this->hasMany('Calendar', 'sourceType');
    }
}


?>