<?php namespace Calendar\Resource;

use App;

abstract class BaseCalendar implements iRemoteCalendar {

	/**
	* Get calendar in iCal format
	* @param array $params Parameters of how to get calendar data
	* @return string iCal formatted string
	*/
	public function getICal(Array $params){
		return "BaseCalendar Source";
	}

	/**
	* Format calendar data to iCal
	* @param string $data XML, JSON in other data-format
	* @return string Correctly formated iCal data
	*/
	public function dataToICal($data){		
		return 'ical Data';
	}	

}

?>