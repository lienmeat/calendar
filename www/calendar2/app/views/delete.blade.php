@extends('layouts.master')

@section('title')
	{{ $page_title }}
@stop

@section('content')
	<h3>{{ $delete_message }}</h3>
	{{ Form::open() }}
	{{ Form::checkbox('delete_confirm', '1') }} Yes!<br />
	{{ Form::submit('Delete') }}
	{{ Form::close() }}
@stop

@section('js')
	{{ $js }}
@stop