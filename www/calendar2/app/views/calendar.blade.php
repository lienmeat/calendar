@extends('layouts.master')

@section('title')
	{{ $page_title }}
@stop

@section('content')
	<div id="top_content_contain">
		{{ $top_content }}
	</div>
	<div id="calendar_contain">
		@include('fullcalendar', array('calendars'=>$calendars, 'events'=>$events))
	</div>
	<div id="bottom_content_contain">
		{{ $bottom_content }}
	</div>
@stop

@section('js')
	{{ $js }}
@stop