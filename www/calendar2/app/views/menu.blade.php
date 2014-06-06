
<div id="calendar_menu">
	<ul class="menu_main">
		<li>{{ HTML::link('/', 'Main Calendar') }}</li>
		@if (Authority::can('manage', 'Calendar') || true)
			<li>{{ HTML::link('admin', 'Calendar Admin') }}</li>
		@endif
		
	</ul>
</div>