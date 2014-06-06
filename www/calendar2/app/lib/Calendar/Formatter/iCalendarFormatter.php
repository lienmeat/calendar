<?php namespace Calendar\Formatter;

/**
* Intermediary format for calendar data is iCal (currently).
* Calendar resource classes format whatever they recieve to iCal, 
* which is then formatted to the final data representation for rendering.
* This interface should be implemented by all Calendar Formatters
*/

interface ICalendarFormatter{

	/**
	* Takes iCal data, and makes it into whatever our client-side stuff uses
	* @param string $data iCal formatted data
	* @return string Something we can use on the client side (most likely json)
	*/
	public function fromICal($data);

	/**
	* Implement this method in every formatter to get events from ical array format to proper "end" format
	* @param array $events
	* @return string
	* @access public
	*/
	public function fromICalEvents(Array $events);
}

?>