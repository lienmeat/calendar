<?php namespace Calendar\Resource;

interface iRemoteCalendar {

	/**
	* Format calendar data to iCal
	* @param string $data XML, JSON in other data-format
	* @return string Correctly formated iCal data
	*/
	public function dataToICal($source);

	/**
	* Get calendar in iCal format
	* @param array $params Parameters of how to get calendar data
	* @return string iCal formatted string
	*/
	public function getICal(Array $params);
}