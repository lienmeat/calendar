<?php

use Illuminate\Database\Migrations\Migration;

class CreateInitialSchema extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sourceTypes',function($table){
			$table->increments('id');
			$table->string('name',50);
			$table->string('type',30);
		});

		Schema::create('calendars',function($table){
			$table->increments('id');
			$table->string('name',50)->unique();
			$table->timestamps();
			$table->integer('sourceType')->unsigned();
			$table->foreign('sourceType')->references('id')->on('sourceTypes');
			$table->text('config');
			$table->enum('updateInterval' ,array('DAY','WEEK','MONTH','YEAR'));
			$table->boolean('default');
			$table->text('color');
			$table->text('textColor');
			$table->softDeletes();
		});
		
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
		

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('calendars');
		Schema::dropIfExists('sourceTypes');
		Schema::dropIfExists('events');
	}

}