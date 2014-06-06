<?php

class Calendar extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'calendars';

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
	public $timestamps = true;

	/**
	* Enable soft deleting!
	*/
	protected $softDelete = true;


	/**
	* Accesses events on a calendar
	*/
	public function events()
    {
        return $this->hasMany('CalendarEvent');
    }

    /**
	* Accesses sourceType on a calendar
	*/
	public function sourceType()
    {
        return $this->belongsTo('SourceType', 'sourceType');
    }	
}


?>