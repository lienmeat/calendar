<?php namespace Calendar\Resource;

class GoogleCalendar extends BaseCalendar implements iRemoteCalendar {

	/**
	* Get .ics file contents from google calendar location
	*/
	public function getICal(Array $params){
		if( $params['location'] ) {
			return file_get_contents( $params['location'] );
		}else{
			return "";
		}
	}	
}

?>