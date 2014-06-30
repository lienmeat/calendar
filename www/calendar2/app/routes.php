<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'CalendarController@getIndex');

Route::get('admin', 'CalendarController@getAdmin');

Route::get('oauth2callback', 'LoginController@getLoginwithgoogle');

Route::controller('calendars', 'CalendarController');
Route::controller('login', 'LoginController');
Route::controller('sourcetypes', 'SourceTypeController');
Route::controller('events', 'CalendarEventController');

//Route::get('events/{id}', 'CalendarController@getEvent');

// Route::get('auth', function(){
// 	$res = Authority::can('manage', 'blarg');
// 	var_dump($res);
// });



Route::get('fix', function(){
	$ical = file_get_contents("https://www.google.com/calendar/ical/dmuig87tiu2jne9b7i5epn0ekc%40group.calendar.google.com/public/basic.ics");	
	$res = iCalReader::readSource($ical);
	echo "<pre>".print_r($res, true)."</pre>";	
});