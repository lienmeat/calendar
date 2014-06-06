<?php

use Illuminate\Database\Migrations\Migration;

class UpdateCalendar extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('calendars',function($table){
			$table->enum('updateInterval' ,array('DAY','WEEK','MONTH','YEAR'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('calendars',function($table){
			$table->dropColumn('updateInterval');
		});			
	}

}