<fieldset>
<legend>iCal (.ics) Upload Configuration</legend>
<p>
If you use this option, whenever you wish this application's calendar to be updated, you must upload a new iCal (.ics) file. Because of this drawback, the "Google Calendar" option is recommended. However, the upside to this option is you can use any software you want to edit your calendar as long as it has "Export to iCal" functionality.
</p>

<p>
{{ Form::label('config[location]', 'Select an iCal/.ics file to upload:') }}<br />
{{ Form::file('location') }}
</p>
</fieldset>