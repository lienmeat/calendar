<?php namespace Calendar\Formatter;

use App;

abstract class BaseCalendarFormatter implements iCalendarFormatter{

	/**
	* holds iCalData as array so you never have to run iCalToArray() more than once
	* @access public
	*/
	public $iCalDataArray;

	/**
	* Implement this method in every formatter to get from ical format to proper "end" format
	* @param string $data
	* @return string
	* @access public
	*/
	public function fromICal($data){
		return "formatted";
	}

	/**
	* Implement this method in every formatter to get events from ical array format to proper "end" format
	* @param array $events
	* @return string
	* @access public
	*/
	public function fromICalEvents(Array $events){
		return "formatted";
	}

	/**
	* Parses iCal to Array
	* @param string $data iCal data
	* @return Array representation of iCal data
	* @access public
	*/
	public function iCalToArray($data){		
		return iCalReader::readSource($data);
	}
}

?>