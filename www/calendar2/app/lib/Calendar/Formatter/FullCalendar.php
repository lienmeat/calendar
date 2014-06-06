<?php namespace Calendar\Formatter;

/**
* Formats iCal data into JSON usable with FullCalendar
*/

class FullCalendar extends BaseCalendarFormatter implements iCalendarFormatter{

	public function fromICal($data){
		$data = $this->iCalToArray($data);
		//echo "<pre>".print_r($data, true)."</pre>";
		$events = array();		

		if($data['VEVENT']) {
			//loop over all the events
			foreach($data['VEVENT'] as $event){
				$tmp = new \stdClass();
				$tmp->title = $event['SUMMARY'];			
						
				//if there is no time component to the timestamp, it is a full day event (So says me, anyway)
				if( strlen($event['DTEND']) == 8 ) {				
					$tmp->allDay = "true";

					//just doing strtotime will mess up full calendar, since it's a GMT-aware calendar...lol
					//it's stupid in all the smartest ways
					$tmp->start = date('Y-m-d', strtotime($event['DTSTART']));
					//stupidly, full calendar end dates are inclusive for all day events, otherwise exclusive
					//so we are subtracting a day from the date
					$tmp->end = date('Y-m-d', strtotime($event['DTEND'])-86400);

				}else{
					$tmp->start = strtotime($event['DTSTART']);			
					$tmp->end = strtotime($event['DTEND']);
				}

				if($event['CATEGORIES']){
					$tmp->CATEGORIES = $event['CATEGORIES'];
				}

				if($event['RRULE']){
					$tmp->RRULE = $event['RRULE'];
				}

				$tmp->location = $event['LOCATION'];
				$tmp->description = $event['DESCRIPTION'];				
				$events[] = $tmp;
			}
		}

		return json_encode($events);
	}

	/**
	* Take a ical events in array format and make it calendar event format (json in this case)
	* @param array $events  iCal VEVENT entries parsed into array format
	* 
	*/
	public function fromICalEvents(Array $events){
		$tmp_arr = array();
		if($events){
			foreach($events as $event){
				if(is_object($event)){
					$event = (array) $event;
				}
				if( is_array($event) ) {
					$tmp = new \stdClass();
					$tmp->title = $event['SUMMARY'];			
					
					//if there is no time component to the timestamp, it is a full day event (So says me, anyway)
					if( strlen($event['DTEND']) == 8 ) {				
						$tmp->allDay = "true";
						//just doing strtotime will mess up full calendar, since it's a GMT-aware calendar...lol
						//it's stupid in all the smartest ways
						$tmp->start = date('Y-m-d', strtotime($event['DTSTART']));
						//stupidly, full calendar end dates are inclusive for all day events, otherwise exclusive
						//so we are subtracting a day from the date
						$tmp->end = date('Y-m-d', strtotime($event['DTEND'])-86400);

					}else{
						$tmp->start = strtotime($event['DTSTART']);			
						$tmp->end = strtotime($event['DTEND']);
					}

					if($event['CATEGORIES']){
						$tmp->CATAGORIES = $event['CATEGORIES'];
					}

					if($event['RRULE']){
						$tmp->RRULE = $event['RRULE'];
					}

					$tmp->location = $event['LOCATION'];
					$tmp->description = $event['DESCRIPTION'];
					$tmp->id = $event['id'];
					$tmp_arr[] = $tmp;
				}
			}
		}
		return json_encode($tmp_arr);
	}
}

?>