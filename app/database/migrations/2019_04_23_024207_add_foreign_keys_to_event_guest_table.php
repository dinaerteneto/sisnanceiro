<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEventGuestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('event_guest', function(Blueprint $table)
		{
			$table->foreign('event_id', 'event_guest_ibfk_1')->references('id')->on('event')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('person_id', 'event_guest_ibfk_2')->references('id')->on('person')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('event_guest', function(Blueprint $table)
		{
			$table->dropForeign('event_guest_ibfk_1');
			$table->dropForeign('event_guest_ibfk_2');
		});
	}

}
