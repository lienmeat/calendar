@extends('blank')

@section('title')
	{{ $title }}
@stop

@section('content')
	<?php	
	$sourceTypeOpts = array();
	if($sourceTypes){
		$sourceTypeOpts[null] = 'Select One';
		foreach ($sourceTypes as $s) {
			$sourceTypeOpts[$s->id] = $s->name;
		}
	}
	?>

	<span class="errors"> 
		@foreach($errors->all(":message<br />") as $message)
			{{ $message }}
		@endforeach
	</span>
	
	{{ Form::model($calendar, array('files'=>true) ) }}
	<p>
	{{ Form::label('name', 'Name:') }}<br />
	{{ Form::text('name') }}<br />
	</p>
	<p>
	{{ Form::label('default', 'Will this calendar\'s events show up by default on the main calendar? (If not, it will still be toggle-able.)') }}<br />
	{{ Form::radio('default', '1') }}&nbsp;Yes&nbsp;&nbsp;{{ Form::radio('default', '0'); }}&nbsp;No
	</p>
	<p>
	<?php
	if($calendar && !$calendar->color) {
		$calendar->color = "#000000";
	}
	?>
	
	{{ Form::label('color', 'Color of event background:') }}<br />	
	{{ Form::text('color', $calendar->color, array('id'=>'calendar_color', 'size'=>7)) }}
	</p>
	<p>
	
	<?php
	if($calendar && !$calendar->textColor) {
		$calendar->textColor = "#ffffff"; 
	}
	?>
	
	{{ Form::label('textColor', 'Color of event text:') }}<br />	
	{{ Form::text('textColor', $calendar->textColor, array('id'=>'calendar_text_color', 'size'=>7)) }}
	</p>
	<p>
	{{ Form::label('sourceType', 'What type of calendar source do you want to use?') }}<br />
	{{ Form::select('sourceType', $sourceTypeOpts, $calendar->sourceType, array('id'=>'sourceType_select')) }}
	</p>
	<div id="sourcetype_config_contain">
	</div>
	<p>{{ Form::submit('Save') }}</p>
	{{ Form::close() }}
	
@stop

@section('js')	
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" media="all" />
	{{ HTML::script('JS/colorpicker/jquery.colorpicker.js') }}
	{{ HTML::style('JS/colorpicker/jquery.colorpicker.css') }}
	<script>
	$('#sourceType_select').on('change', getSourceTypeConfig);
	$(document).ready(function(){
			getSourceTypeConfig();
			bindColorPickers();
		}
	);

	function getSourceTypeConfig() {
		var sourceType = $('#sourceType_select').val();
		if(sourceType){
			$.ajax(
				{
					url: '{{ url("sourcetypes/config-view-for") }}/'+sourceType,
					dataType: 'json',
					type: 'POST',
					data: @if( $calendar->config ){{'{config: '.$calendar->config.'}'}}@else{}@endif,
					success: renderSourceTypeConfig
				}
			);
		}
	}

	function renderSourceTypeConfig(resp){
		if(resp && resp.status == 'success' && resp.html){
			$('#sourcetype_config_contain').html(resp.html);
		}		
	}

	function bindColorPickers(){		
		$('#calendar_color').colorpicker({
			colorFormat: '#HEX',
			altField: '#calendar_color',			
		});
		$('#calendar_text_color').colorpicker({
			colorFormat: '#HEX',
			altField: '#calendar_text_color',			
		});
	}
	</script>			
@stop