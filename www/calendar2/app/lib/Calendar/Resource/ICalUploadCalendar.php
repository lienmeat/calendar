<?php namespace Calendar\Resource;

class ICalUploadCalendar extends BaseCalendar implements iRemoteCalendar {

	/**
	* Get .ics file contents from google calendar location
	*/
	public function getICal(Array $params){
		//die(print_r($params, true));
		if( $params['location'] ) {
			try{
				return file_get_contents( $params['location'] );	
			}catch(Exception $e){
				return "";
			}
			
		}else{
			return "";
		}
	}	
}

?>