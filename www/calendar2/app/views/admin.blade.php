@extends('layouts.master')

@section('title')
	Calendar Admin
@stop

@section('content')
	
	@if ($calendars)
		<h3>Calendars</h3>
		<ul>
			<li>{{ HTML::link('calendars/add', 'New Calendar') }}</li>
		@foreach( $calendars as $calendar)
			<li>{{ HTML::link('calendars/show/'.$calendar->id, $calendar->name) }}&nbsp;&nbsp;{{ HTML::link('calendars/edit/'.$calendar->id, 'Edit') }}&nbsp;&nbsp;{{ HTML::link('calendars/update/'.$calendar->id, 'Update Events') }}&nbsp;&nbsp;{{ HTML::link('calendars/delete/'.$calendar->id, 'Delete') }}</li>
		@endforeach			
		</ul>
		{{ $calendars->links() }}
	@endif

	@if ($users)
		<h3>Users</h3>
		<ul>
		@foreach( $users as $user)
			<li>{{ $user->username }}&nbsp;&nbsp;{{ HTML::link('users/edit/'.$user->id, 'Edit') }}</li>
		@endforeach
		</ul>
		{{ $users->links() }}
	@endif
@stop

@section('js')
	{{ $js }}
@stop