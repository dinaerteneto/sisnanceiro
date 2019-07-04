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
			$table->foreign('company_id', 'event_guest_ibfk_3')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('invited_by_id', 'event_guest_ibfk_4')->references('id')->on('event_guest')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('responsable_of_payment', 'event_guest_ibfk_5')->references('id')->on('event_guest')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('payment_method_id', 'event_guest_ibfk_6')->references('id')->on('payment_method')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
			$table->dropForeign('event_guest_ibfk_3');
			$table->dropForeign('event_guest_ibfk_4');
			$table->dropForeign('event_guest_ibfk_5');
			$table->dropForeign('event_guest_ibfk_6');
		});
	}

}
