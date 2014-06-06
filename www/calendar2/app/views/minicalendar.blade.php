<?php 
date_default_timezone_set('America/Los_Angeles');
?>
<script src="//www.wallawalla.edu/calendar/scripts/overlib.js"></script>
<script>

function renderBalloonFor(date){
	var html = '<table cellspacing="5" cellpadding="0" style="max-width:500px" border="0" class="calendarPopup">';	
	var es = events[date];
	if(es && es.length > 0) {
		for(var i in es){
			var id = es[i].id;
			var start_array = es[i].DTSTART.split(/[- :]/);
			var end_array = es[i].DTEND.split(/[- :]/);
			var summary = es[i].SUMMARY.substr(0, 100).replace('\\n', '&nbsp;').replace('\\n', '&nbsp;');
			var description = es[i].DESCRIPTION.substr(0, 100).replace('\\n', '&nbsp;').replace('\\n', '&nbsp;')+"...";		
			var time_date_start = new Date();
			time_date_start.setUTCFullYear(start_array[0]);
			time_date_start.setUTCMonth(start_array[1]);
			time_date_start.setUTCDate(start_array[2]);
			time_date_start.setUTCHours(start_array[3]);
			time_date_start.setUTCMinutes(start_array[4]);
			time_date_start.setUTCSeconds(start_array[5]);
			var time_date_end = new Date();
			time_date_end.setUTCFullYear(end_array[0]);
			time_date_end.setUTCMonth(end_array[1]);
			time_date_end.setUTCDate(end_array[2]);
			time_date_end.setUTCHours(end_array[3]);
			time_date_end.setUTCMinutes(end_array[4]);
			time_date_end.setUTCSeconds(end_array[5]);
			if( (time_date_end.getTime() - time_date_start.getTime()) == 86400000){
				time = "All Day";
			}else{
				var hours = time_date_start.getHours();
				if(hours >= 12) {
					var ampm = 'pm';
					if(hours > 12){
						hours = hours - 12;
					}				
				}else{
					var ampm = 'am';
				}
				if(hours == 0){
					hours = 12;
				}
				var mins = time_date_start.getMinutes();
				if( mins < 1){
					mins = mins+"0";
				}
				time = hours+":"+mins+" "+ampm;
			}
			html+='<tr valign="top"><td width="1%" align="right" valign="top" nowrap>'+time+'</td><td style="background-color: #ebe7dc"><a href="{{ URL::to("events/view") }}/'+id+'"><b>'+summary+'</b></a> - '+description+'<a href="{{ URL::to("events/view") }}/'+id+'">&nbsp;more</a></td></tr>';
		}
	}else{
		html+='<tr valign="top"><td>No events scheduled for this day.</td></tr>';
	}
	html+='</table>';
	openBalloon(html);  	
}

function openBalloon(html){
	overlib(html, STICKY, MOUSEOFF, WRAP, LEFT, ABOVE, OFFSETX, 5, OFFSETY, 5, DELAY, 400, FGCOLOR, '#e1e3dc', BGCOLOR, '#656d48' );
}

function popBalloon(time){
	var time = time || 550;
	try {
		nd(time);
	}catch (e) {}	
}

function goToCal(date){
	window.location = "{{ URL::to('/') }}?date="+date;
}

var events = {{ json_encode($events) }};
</script>
<style>

.minicalendar{
	max-width: 217px;
}

.minicalendar table{
	width: 100%;
}

.minicalendar td{
	text-align: center;
	text-decoration: none;
	color:#747c53;
	border-left: 1px solid #999999;
	font-family: Georgia;
	font-size: 11px; 
}

.minicalendar td:first-child{
	border: none;
}

.minicalendar td:hover, .minicalendar td.minitoday{
	text-decoration: underline;
	cursor: pointer;
	color: #EEEEEE;    
    background-color: #656d48;
}

.minicalendar td.minitoday{
	text-decoration: none;
	background-color: #656d48;
}

.minicalendar td.other_month{
	color: #cccccc;
}

.minicalendar td.other_month:hover{	
	color: #EEEEEE;    
}

.calendarPopup {
	BACKGROUND-IMAGE:url(//www.wallawalla.edu/calendar/images/gradback.jpg);
	background-repeat: no-repeat;
	COLOR: #000000;
	TEXT-DECORATION: none;
	background-color: #ffffff;
	font-family: Georgia;
	font-size: 11px;
}

</style>
<div id="minicalendar" class="minicalendar">
	<table cellspacing="0">
		<tr>
			<th colspan="7" >{{ HTML::link('/', date('F Y')) }}</th>
		</tr>
		<?php
		$hit_one = 0;
		$first = true;
		$month = 0;
		$count = 0;
		$today = date('Y-m-d');
		?>	
		@foreach ($days as $k=>$d)
			<?php
				if ($d == 1){
					$month++;
				}
				$es = $events[$k];
				if(!$es){
					$es = array();
				}
			?>
			@if ($count%7 == 0)
				@if (!$first)
					</tr><tr>			
				@else
					<tr>
					<?php $first = false; ?>
				@endif
			@endif

			@if ($month == 1)
				@if ($k == $today)					
					<?php $today_class = "class=\"minitoday\""; ?>
				@else
					<?php $today_class = ""; ?>
				@endif
				<td onmouseover="renderBalloonFor('{{ $k }}');" onmouseout="popBalloon();" onclick="goToCal('{{ $k }}');" {{ $today_class }}>{{ $d }}</td>
			@else
				<td class="other_month" onmouseover="renderBalloonFor('{{ $k }}');" onmouseout="popBalloon();" onclick="goToCal('{{ $k }}');">{{ $d }}</td>
			@endif
			<?php $count++; ?>
		@endforeach
		</tr>
	</table>
</div>