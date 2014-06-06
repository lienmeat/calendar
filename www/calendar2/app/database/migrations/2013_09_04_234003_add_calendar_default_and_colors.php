<?php

use Illuminate\Database\Migrations\Migration;

class AddCalendarDefaultAndColors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('calendars',function($table){
			$table->boolean('default');
			$table->text('color');
			$table->text('textColor');
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
			$table->dropColumn('default');
			$table->dropColumn('color');
			$table->dropColumn('textColor');
		});
	}

}