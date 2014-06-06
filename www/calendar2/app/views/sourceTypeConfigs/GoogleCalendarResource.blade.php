<fieldset>
	<legend>Google Calendar Configuration</legend>
	<p>
	If you use the "Google Calendar" option, the calendar in this application will be updated automatically when you update your Google calendar.
	This is a "set-and-forget" option, and as such, comes highly recommended over doing a "iCal (.ics) Upload".
	</p>
	<a name="top_gcnf"></a>
	<p>
	{{ Form::label('config[location]', 'Google Calendar iCal Url:') }}<br />
	{{ Form::text('config[location]', $config['location']) }} <a href="javascript:$('#goog_instr_contain').toggle();"> Instructions</a>
	</p>


	<div id="goog_instr_contain" style="display: none;">
		<ol>
			<li>
				<div>
					{{ HTML::image('IMG/GoogleCalendar/step1.png') }}
					<p>
						Go to your <a href="https://www.google.com/calendar" target='_blank'>Google Calendar</a>, click the arrow next to the calendar you wish to use, and click on "Calendar Settings".
					</p>					
				</div>
				<hr>
			</li>
			<li>
				<div>
					{{ HTML::image('IMG/GoogleCalendar/step2.png') }}
					<p>
						Go to the "Share this Calendar" tab, check "Make this calendar public", and then click "Save".
					</p>
				</div>
				<hr>
			</li>
			<li>
				<div>
					{{ HTML::image('IMG/GoogleCalendar/step3.png', '', array('width'=>"700px")) }}
					<p>
						Go to the "Calendar Details" tab, click on the "ICAL" button under the "Calendar Address" section.
					</p>					
				</div>
				<hr>
			</li>
			<li>
				<div>
					{{ HTML::image('IMG/GoogleCalendar/step4.png', '', array('width'=>"700px")) }}
					<p>
						Copy and paste the URL/link that is provided in the pop-up box into the text box above these instructions.
					</p>					
				</div>
			</li>
		</ol>
		<a href="#top_gcnf">Back to top</a>
	</div>
</fieldset>