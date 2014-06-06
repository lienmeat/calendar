<?php //require_once('templates.php'); ?>
<html>
	<head>
		<title>
			@yield('title')
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">		
	</head>
    <body>
        @section('header')
            
        @show
        <div class="content_container">
            @section('menu')
                @include('menu')
            @show
            @yield('content')
        </div>
        @section('footer')
            
        @show

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        @yield('js')
        {{ HTML::style('CSS/main.css') }}
</html>
