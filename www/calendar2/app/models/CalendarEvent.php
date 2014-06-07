<?php

class CalendarEvent extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'events';

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
	* Accesses Calendar Obj
	*/
	public function calendar()
    {
        return $this->belongsTo('Calendar');
    }
	

	public static function getBetweenOnCalendar($calendar_id, $start_date, $end_date){
		$start_date = gmdate('Y-m-d H:i:s', strtotime($start_date) );
		$end_date = gmdate('Y-m-d H:i:s', strtotime($end_date) );
		$args = array($calendar_id, $start_date, $end_date, $start_date, $end_date);
		return CalendarEvent::whereRaw("`calendar_id` = ? AND ( `RRULE` IS NOT NULL OR (`DTSTART` BETWEEN ? AND ? OR `DTEND` BETWEEN ? AND ? ) )", $args)->orderBy('DTSTART', 'asc')->get();
	}

	/**
	* Creates events for a calendar given an ical representation
	* @param string $calendar_id
	* @param string $ical Raw, untouched ical datum	
	*/
	public static function createFromIcal($calendar_id, $ical){
			
		$events = array();
		$ical_arr = iCalReader::readSource($ical);
		
		//parse out all the events and save them to db
		if(is_array($ical_arr) and !empty($ical_arr['VEVENT'])){
			
			foreach ($ical_arr['VEVENT'] as $e) {
				$event = new CalendarEvent;
				$event->calendar_id = $calendar_id;
				$event->SUMMARY = $e['SUMMARY'];
				$event->DESCRIPTION = $e['DESCRIPTION'];
				$event->DTSTART = date('Y-m-d H:i:s', strtotime($e['DTSTART']));
				$event->DTEND = date('Y-m-d H:i:s', strtotime($e['DTEND']));
				if($e['RRULE']){
					$event->RRULE = $e['RRULE'];
				}else{
					$event->RRULE = NULL;
				}
				if($e['LOCATION']){
					$event->LOCATION = $e['LOCATION'];					
				}else{
					$event->LOCATION = NULL;
				}
				if($e['CATEGORIES']){
					$event->CATEGORIES = $e['CATEGORIES'];
				}else{
					$event->CATEGORIES = NULL;
				}				
				$event->ical = json_encode($e);
				$ts = date('Y-m-d H:i:s');
				$event->created_at = $ts;
				$event->updated_at = $ts;
				$events[] = $event->toArray();
			}
			CalendarEvent::insert($events);
		}		
	}
	
}


?>