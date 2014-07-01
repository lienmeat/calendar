@section('js')
	{{ HTML::script('JS/fullcalendar-1.6.3/fullcalendar/fullcalendar.min.js') }}
	{{ HTML::script('JS/fullcalendar-1.6.3/jquery/jquery-ui-1.10.3.custom.min.js') }}
	{{ HTML::style('CSS/fullcalendar/fullcalendar.css') }}
	{{ HTML::style('CSS/fullcalendar/fullcalendar.print.css', array('media'=>'print') ) }}
	<script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.js"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.css">
	<style>
	#calendar_tabs_contain {	
		padding: 5px;
	}
	#calendar_tabs {
		list-style: none;
		padding: 0px;
		margin: 0px;		
	}
	#calendar_tabs li{
		display: inline;
		padding: 5px;
		margin-right: 3px;
		border-radius: 3px;
	}
	.fc-event {
		cursor: pointer;
	}
	</style>

	<script>
	<?php
	//set up what view we want to see and on what date
	$cal_date = Input::get('date');
	?>
	@if( $cal_date )
		<?php 
		$cal_date = new DateTime($cal_date);
		?>
		var gotodate = new Date('{{ gmdate("Y/m/d", $cal_date->getTimestamp()) }}');
	@endif

	@if ( !empty( $calendars ) )
		var calendars = {{ json_encode($calendars); }};
	@endif

	var sources_added = [];

	function renderFullCalendar(){	
		
		$('#fullcalendar').fullCalendar(
			{
				header: {
					right: 'today prev,next',
					center: 'title',
					left: 'month,agendaWeek,agendaDay'
				},
				defaultView: 'month',
				allDayDefault: false,
				eventRender: function(event, element) {
					$(element).addClass('eventsource_'+event.source.id);
					$(element).qtip({
						content: renderQtip(event)
					});
					$(element).on('click', function(e){						
						window.location = "{{ URL::to('events/view') }}/"+event.id;
					});
					
	    		}
			}
		);

		compileEventSources(calendars);

		if(gotodate){
			console.log(gotodate);
			$('#fullcalendar').fullCalendar('gotoDate', gotodate).fullCalendar('changeView', 'agendaDay');
		}
	}

	function renderQtip(event){
		var out = "Description: "+event.description;
		if(event.location){
			out+="<br />Location: "+event.location;
		}
		return out;

	}

	/**
	* Compiles default calendars into event sources
	*/
	function compileEventSources(calendars){
		for(var i in calendars){		
			if(calendars[i].default == 1){			
				addEventSource(calendars[i].id);
			}
			renderCalendarTab(calendars[i]);
		}	
	}

	function addEventSource(calendar_id){
		if(sources_added.indexOf(calendar_id) < 0){
			for(var i in calendars){
				if(calendars[i].id == calendar_id){
					var tmp = calendars[i];
					tmp.cache = true;
					$('#fullcalendar').fullCalendar('addEventSource', tmp);
					sources_added.push(calendars[i].id);
					break;
				}
			}
		}	
	}

	function removeEventSource(calendar_id){	
		var src_idx = sources_added.indexOf(calendar_id);
		if(src_idx >= 0){
			for(var i in calendars){
				if(calendars[i].id == calendar_id){
					var tmp = calendars[i];
					tmp.cache = true;
					$('#fullcalendar').fullCalendar('removeEventSource', tmp);
					sources_added.splice(src_idx, 1);
					break;
				}
			}
		}	
	}

	function renderCalendarTab(calendar){
		var html = "";
		if(calendar && calendar.id){
			if(calendar.default == 1){
				var checked = "checked=\"checked\"";
			}else{
				var checked = "";
			}

			html+="<li style=\"background-color: "+calendar.color+"; color: "+calendar.textColor+";\"><input id=\"caltabcbx_"+calendar.id+"\" type=\"checkbox\" value=\""+calendar.id+"\" onChange=\"toggleEventSource(this);\" "+checked+"><label for=\"caltabcbx_"+calendar.id+"\">"+calendar.name+"</label></li>";
			$('#calendar_tabs').append(html);
		}
	}

	function toggleEventSource(checkbox){
		var cal_id = $(checkbox).val();
		if($(checkbox).prop('checked')){
			addEventSource(cal_id);
		}else{		
			removeEventSource(cal_id);
		}
		$('#fullcalendar').fullCalendar('rerenderEvents');
	}


	$(document).ready(renderFullCalendar);
	</script>
@stop

<div id="calendar_tabs_contain">
	<ul id="calendar_tabs"></ul>
</div>
<div id="fullcalendar">

</div>
{{ var_dump(Auth::user()) }}