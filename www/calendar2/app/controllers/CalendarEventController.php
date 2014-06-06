<?php

class CalendarEventController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| CalendarEvent Controller
	|--------------------------------------------------------------------------
	|
	| Route::get('events', 'CalendarEventController@<method name>');
	|
	*/
	public function getView($id){
		$event = CalendarEvent::find($id);
		return View::make('event')->with('event', $event);
	}
	
}