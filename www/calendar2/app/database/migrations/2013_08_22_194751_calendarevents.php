<?php

use Illuminate\Database\Migrations\Migration;

class Calendarevents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events',function($table){
			$table->increments('id');
			$table->integer('calendar_id')->unsigned();
			$table->foreign('calendar_id')->references('id')->on('calendars');
			$table->text('SUMMARY')->nullable();
			$table->text('DESCRIPTION')->nullable();
			$table->dateTime('DTSTART');
			$table->dateTime('DTEND');
			$table->text('RRULE')->nullable();
			$table->text('LOCATION')->nullable();
			$table->text('CATEGORIES')->nullable();
			$table->timestamps();
			$table->text('ical');
		});

		Schema::table('calendars',function($table){
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('events');
		Schema::table('calendars',function($table){
			$table->dropColumn('deleted_at');
		});
	}

}