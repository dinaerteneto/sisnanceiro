<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventGuestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_guest', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index('event_id');
			$table->integer('person_id')->unsigned()->index('person_id');
			$table->string('email')->default('');
			$table->string('token_email')->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
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
		Schema::drop('event_guest');
	}

}
