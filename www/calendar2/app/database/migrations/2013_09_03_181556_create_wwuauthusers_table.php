<?php

use Illuminate\Database\Migrations\Migration;

class CreateWwuauthusersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wwuauthusers',function($table){
			$table->increments('id');
			$table->text('username');
		});		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('wwuauthusers');
	}

}