<?php

use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration {

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
	}

}