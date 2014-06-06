<?php
/**
* All IOC registration should happen here
*/

App::bind('GoogleCalendarResource', function($app)
{
   return new Calendar\Resource\GoogleCalendar;
});

App::bind('ICalUploadResource', function($app)
{
   return new Calendar\Resource\ICalUploadCalendar;
});

App::bind('iCalReader', function($app)
{
   return new iCalReader\iCalReader;
});

App::bind('CalendarFormatter', function($app)
{
   return new Calendar\Formatter\FullCalendar;
});

?>