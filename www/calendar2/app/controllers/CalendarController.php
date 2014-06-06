<?php

class CalendarController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Calendar Controller
	|--------------------------------------------------------------------------
	|
	| Route::get('Calendars', 'CalendarController@<method name>');
	|
	*/
	private $calendar_cache_lifetime = 43200;

	public function getAdmin(){
		$this->_canManage();
		$data['calendars'] = Calendar::orderBy('name', 'asc')->paginate(20);
		//$data['users'] = User::orderBy('username', 'asc')->paginate(20);
		return View::make('admin', $data);
	}

	public function getIndex(){
		return $this->getShowAll();
	}

	public function getMiniCalendar(){
		$today = new \DateTime();
		$delta = new DateInterval("P1D");
		$year = $today->format('Y');
		$month = $today->format('m');
		$day = $today->format('d');
		$prev_month = new \DateTime('last month');
		$next_month = new \DateTime('next month');
		$last_day_current_month = $today->format('t');
		$data['last_day_prev_month'] = $last_day_prev_month = $prev_month->format('t');		
		$data['week_day_month_start'] =  $week_day_month_start = date('w', strtotime("$year-$month-01"))+1;
		
		//previous month may have days to be displayed
		$data['days'] = array();
		if($week_day_month_start != 1){			
			$start = new DateTime($prev_month->format('Y-m')."-".($last_day_prev_month - (7-$week_day_month_start)));			
		}else{
			$start = new DateTime("$year-$month-01");
		}		
		$cur = clone($start);
		$count = 1;
		while($count < 43){
			$data['days'][$cur->format('Y-m-d')] = $cur->format('j');
			$cur->add($delta);
			$count++;
		}
		$end = clone($cur->sub($delta));
		//echo "s: ".$start->format('Y-m-d')." e: ".$end->format('Y-m-d');
		$calendars = Calendar::where('default', '=', '1')->get();
		$events = array();
		foreach($calendars as $c){
			$es = CalendarEvent::getBetweenOnCalendar($c->id, $start->format('Y-m-d'), $end->format('Y-m-d'))->toArray();
			$events = array_merge($events,  $es);
		}

		$data['events'] = array();
		$out = "";
		//make events have the right time for this timezone, create events array
		foreach ($events as $event) {			
			$e_start = new \DateTime($event['DTSTART']);
			$e_start->setTimezone(new \DateTimeZone('America/Los_Angeles'));
			
			$e_end = new \DateTime($event['DTEND']);
			$e_end->setTimezone(new \DateTimeZone('America/Los_Angeles'));
			
			//check to see if this is an all-day event
			$tmp = clone($e_start);
			$tmp->setTimezone(new \DateTimeZone('America/Los_Angeles'));
			if( $tmp->add($delta) == $e_end ){
				//easiest way to get the time back to midnight, ranther than trying to set the right time
				//since the original time of all-days is midnight
				$e_end = $e_start->setTimezone(new \DateTimeZone('UTC'));
			}			
			//make events span multiple days if needed!
			$cur_date = clone($e_start);
			while($cur_date <= $e_end){
			 	unset($event['ical']);
			 	$data['events'][$cur_date->format('Y-m-d')][] = $event;
			 	$cur_date->add($delta);
			}
		}	
		//$data['debug'] = $out;
		return View::make('minicalendar', $data);
		
	}	

	public function getUpdateAll(){
		//$successful = array();
		//$failed = array();
		$calendars = Calendar::All();
		
		//this IS more efficent than using the ORM to do this while
		//looping over calendars.  There are FEW SourceTypes, and potentially TONS
		//of calendars.
		$source_types = SourceType::All();
		$source_index = array();
		foreach ($source_types as $source) {
			$source_index[$source->id] = $source->type;
		}

		if(!empty($calendars)){
			foreach ($calendars as $calendar) {
				$this->_updateEvents($calendar);
			}
			//Cache::flush();
		}
		return 'finished';
	}

	public function getUpdate($id){
		$calendar = Calendar::find($id);
		if($calendar){
			$ret = $this->_updateEvents($calendar);
		}
		if($ret){
			return View::make('blank')->with('content', 'Calendar Updated!');
		}else{
			return View::make('blank')->with('content', 'There was an error updating this calendar!');
		}
	}

	/**
	* Updates all the events on a calendar so they are current
	*/
	public function _updateEvents($calendar){
		$calendar->events()->delete();
		$sourceType = $calendar->sourceType()->get()->first();
		$config = (array) json_decode($calendar->config);
		$ical = App::make($sourceType->type)->getICal($config);
		if($ical){
			CalendarEvent::createFromICal($calendar->id, $ical);
		}
		//Cache::flush();
		return true;		
	}

	/**
	* Show a particular calendar
	* @param string $id Calendar id
	*/
	public function getShow($id){
		$calendar = Calendar::find($id);		
		//$events = array();
		if($calendar){
			$start_date = date('Y-m', strtotime('-6 months'))."-01 00:00:00";
			$end_date = date('Y-m', strtotime('+6 months'))."-01 00:00:00";
			//$events[$id] = $this->_getCalendarEvents($calendar->id, $start_date, $end_date);
			return View::make('calendar')->with('page_title', $calendar->name.' Calendar')->with('calendars', array( $this->_formatCalendar($calendar) ) );
		}else{
			return View::make('blank')->with('content', "No Calendar with that ID")->with('page_title', 'Error');
		}
	}

	/**
	* Show calendars	
	*/
	public function getShowAll(){
		$calendars = Calendar::All();
		$cal_arrs = array();
		//$events = array();
		$start_date = date('Y-m', strtotime('-6 months'))."-01 00:00:00";
		$end_date = date('Y-m', strtotime('+6 months'))."-01 00:00:00";
		if($calendars){			
			foreach($calendars as $c){
				//$events[$c->id] = $this->_getCalendarEvents($c->id, $start_date, $end_date);
				$cal_arrs[] = $this->_formatCalendar($c);				
			}
			return View::make('calendar')->with('page_title', 'All Calendars')->with('calendars', $cal_arrs );
		}else{
			return View::make('blank')->with('content', "No Calendars Exist!")->with('page_title', 'Error');
		}
	}

	

	public function getEdit($id){
		$this->_canManage();
		$data['calendar'] = Calendar::find($id);
		$data['title'] = "Edit Calendar $id";
		if($data['calendar']){
			$data['sourceTypes'] = SourceType::All();
			return View::make('calendar_add', $data);
		}else{
			return View::make('blank')->with('content', 'Calendar with id: $id does not exist!');
		}		
	}

	public function postEdit($id){
		$this->_canManage();
		$data = Input::all();		
		$files = Input::file();
		$rules = array('name'=>array('required', 'regex:/[a-zA-Z0-9\ ]/'), 'sourceType'=>'required|integer', 'default'=>'required', 'color'=>'required', 'textColor'=>'required');
		$messages = array(
			'name.required'=>'You must name your calendar!',
			'name.regex'=>'The calendar name must only consist of letters, numbers, and spaces!',			
			'sourceType.required'=>'You must choose and configure a calendar source!',
			'default.required'=>'Please choose whether this calendar events will show up by default!',
			'color.required'=>'You must choose a color for the event background!',
			'textColor.required'=>'You must choose a color for the event text!',
		);
		$validator = $this->_validation($data, $rules, $messages);
		if($validator->fails()){
			return Redirect::to('calendars/edit/'.$id)->withErrors($validator)->withInput();
		}else{
			if(!empty($files)){				
				$uploads = $this->_doUploads($files);
				if($uploads){
					foreach($uploads as $var=>$path){
						$data['config'][$var] = $path;
					}
				}
			}
			$data['config'] = json_encode($data['config']);
			unset($data['_token']);
			
			$calendar = Calendar::find($id);
			$calendar->name = $data['name'];
			$calendar->default = $data['default'];
			$calendar->color = $data['color'];
			$calendar->textColor = $data['textColor'];
			$calendar->sourceType = $data['sourceType'];
			$calendar->config = $data['config'];
			$calendar->save();
			$calendar->events()->delete();
			$this->_updateEvents($calendar);
			return Redirect::to('/');
		}
	}

	public function getAdd(){
		$this->_canManage();
		//make sure user is allowed to make calendars
		$data['title'] = 'New Calendar';
		$data['sourceTypes'] = SourceType::All();
		return View::make('calendar_add', $data);
	}

	public function postAdd(){
		$this->_canManage();
		$data = Input::all();		
		$files = Input::file();
		$rules = array('name'=>array('required', 'regex:/[a-zA-Z0-9\ ]/', 'unique:calendars'), 'sourceType'=>'required|integer', 'default'=>'required', 'color'=>'required', 'textColor'=>'required');
		$messages = array(
			'name.required'=>'You must name your calendar!',
			'name.regex'=>'The calendar name must only consist of letters, numbers, and spaces!',
			'name.unique'=>'The calendar name you chose is already in use! Please choose a different name!',
			'sourceType.required'=>'You must choose and configure a calendar source!',
			'default.required'=>'Please choose whether this calendar events will show up by default!',
			'color.required'=>'You must choose a color for the event background!',
			'textColor.required'=>'You must choose a color for the event text!',
		);
		$validator = $this->_validation($data, $rules, $messages);
		if($validator->fails()){
			return Redirect::to('calendars/add')->withErrors($validator)->withInput();
		}else{			
			if(!empty($files)){				
				$uploads = $this->_doUploads($files);
				if($uploads){
					foreach($uploads as $var=>$path){
						$data['config'][$var] = $path;
					}
				}
			}
			$data['config'] = json_encode($data['config']);
			unset($data['_token']);
			
			$calendar = new Calendar;
			$calendar->name = $data['name'];
			$calendar->default = $data['default'];
			$calendar->color = $data['color'];
			$calendar->textColor = $data['textColor'];
			$calendar->sourceType = $data['sourceType'];
			$calendar->config = $data['config'];
			$calendar->save();
			$this->_updateEvents($calendar);			
			return Redirect::to('/');
		}
	}

	public function _doUploads(){
		$files = Input::file();		
		$moved_to = array();
		$path = storage_path().'/uploads/';
		if($files){
			foreach ($files as $inputname=>$f) {
				if(Input::hasFile($inputname)){
					$filename = str_replace('.', '', uniqid('fileupload_', true));
					Input::file($inputname)->move($path, $filename);
					$moved_to[$inputname] = $path.$filename;
				}
			}
			return $moved_to;
		}else{
			return false;
		}
	}

	public function _validation($data, $rules, $messages){
		return Validator::make( $data, $rules, $messages);
	}

	public function getDelete($id){
		$this->_canManage();
		$data['calendar'] = Calendar::find($id);
		if($data['calendar']){
			$data['delete_message'] = 'Are you sure you want to delete the calendar named "'.$data['calendar']->name.'"?';
			return View::make('delete', $data);
		}
	}

	public function postDelete($id){
		$this->_canManage();
		$rules = array('delete_confirm'=>'required');
		$messages = array(
			'delete_confirm.required'=>'Do you want to delete this or not?  Confirmation is required.',			
		);
		$validator = $this->_validation(Input::all(), $rules, $messages);		
		if($validator->fails()){
			return Redirect::to('calendars/delete')->withErrors($validator)->withInput();
		}else{
			$calendar = Calendar::find($id);
			$calendar->delete();
			return Redirect::to('admin');
		}
	}	

	public function getCalendarEvents($calendar_id){		
		$start = date('Y-m-d H:i:s', Input::get('start'));		
		$end = date('Y-m-d H:i:s', Input::get('end'));		
		if(!$start){
			$start = date('Y-m-d H:i:s', strtotime('-1 month'));
		}
		if(!$end){
			$end = date('Y-m-d H:i:s', strtotime('+1 month'));
		}		
		return $this->_getCalendarEvents($calendar_id, $start, $end);
	}

	/**
	* Gets calendar events given it's id
	* @param string $calendar_id
	* @return string
	*/
	public function _getCalendarEvents($calendar_id, $start_date=null, $end_date=null){
		if(!$start_date){
			$start_date = date('Y-m-d H:i:s', strtotime('-6 months'));
		}
		if(!$end_date){
			$end_date = date('Y-m-d H:i:s', strtotime('+6 months'));
		}
		if(!empty($calendar_id)) {
			$key = $calendar_id.'|'.$start_date."|".$end_date;			
			//$events = Cache::get($key);
			$events = array();
			if(empty($events)){				
				$events = $this->_formatCalendarEvents($calendar_id, $start_date, $end_date);
				//Cache::put($key, $events, $this->calendar_cache_lifetime);
			}						
		}
		return $events;
	}

	/**
	* Formats calendar events that exist on a given date range into the calendar end format to be cached/displayed
	* @param string $calendar_id
	* @param string $start_date
	* @param string $end_date
	*/
	public function _formatCalendarEvents($calendar_id, $start_date=null, $end_date=null){
		if(!$start_date){
			$start_date = date('Y-m-d H:i:s', strtotime('-6 months'));
		}
		if(!$end_date){
			$end_date = date('Y-m-d H:i:s', strtotime('+6 months'));
		}
		
		$ical_events = array();

		$events = CalendarEvent::getBetweenOnCalendar($calendar_id, $start_date, $end_date);

		if($events){
			foreach ($events as $e) {
				$ical = json_decode($e->ical);
				$ical->id = $e->id;
				//if there is an RRULE defined, do extra work
				if($e->RRULE){
					$this->_makeEventsFromRRULE($ical, $start_date, $end_date, $ical_events);
				}else{
					$ical_events[] = $ical;
				}
			}
			
		}

		return App::make('CalendarFormatter')->fromICalEvents($ical_events);		
	}

	/**
	* Horible thing to convert rruled events into individual ical events
	* @param array $ical_event Array formated iCal event
	* @param string $start_date
	* @param string $end_date
	* @param array $arr where events will be added
	*/
	public function _makeEventsFromRRULE($ical_event, $start_date, $end_date, &$arr){
		//todo:  yes
	}

	/**
	* Formats a calendar row for showing on the calendar view page
	* @param object $calendar_row
	* @return object
	*/
	public function _formatCalendar($calendar_row){
		if(!empty($calendar_row)){
			$arr = $calendar_row->toArray();
			$config = json_decode($arr['config']);
			if(!empty($config)){
				foreach($config as $k=>$v) {
					$arr[$k] = $v;
				}
			}
			$arr['url'] = url('calendars/calendar-events/'.$calendar_row->id);
			unset($arr['config']);
			return $arr;
		}
		return false;
	}

	public function _canManage(){
		// Auth::attempt(array());
		// if(!Authority::can('manage', 'Calendar')){
		// 	die(View::make('blank')->with('content', 'You don\'t have sufficient permissions to manage calendars!'));
		// }
	}
}