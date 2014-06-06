@extends('layouts.master')

@section('title')
	{{ $event->SUMMARY }}
@stop

@section('content')
	<?php
	$start = new \DateTime($event->DTSTART);
	$start->setTimezone(new \DateTimeZone('America/Los_Angeles'));
	$end = new \DateTime($event->DTEND);
	$end->setTimezone(new \DateTimeZone('America/Los_Angeles'));
	$cmp = clone($start);	
	?>
	{{ HTML::link('/?date='.$start->format('Y-m-d'), '<-- Go to Full Calendar') }}	
	<h1>{{ $event->SUMMARY }}</h1>
	
	<div class="event_contents">
	@if ($end == $cmp->add(new \DateInterval('P1D')))
		<h5>{{ $start->setTimezone(new DateTimeZone('UTC'))->format('l F d Y') }} - All Day Event </h5>
	@else
		<h5>Start: {{ $start->format('l F d Y g:i a') }}<br />End: {{ $end->format('l F d Y g:i a') }} </h5>
	@endif
	<p>{{ $event->DESCRIPTION }}</p>
	@if ($event->LOCATION)
		<p>Location: {{ $event->LOCATION }}</p>
	@endif
	</div>	
@stop

@section('js')
	<style>
		.event_contents{
			padding-left: 10px;
		}
	</style>
@stop