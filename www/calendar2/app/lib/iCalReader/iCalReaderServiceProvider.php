<?php namespace iCalReader;
use Illuminate\Support\ServiceProvider;

class iCalReaderServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('icalreader', function()
        {
            return new iCalReader;
        });
    }

}