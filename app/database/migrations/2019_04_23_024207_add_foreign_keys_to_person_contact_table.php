<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPersonContactTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('person_contact', function(Blueprint $table)
		{
			$table->foreign('person_id', 'person_contact_ibfk_1')->references('id')->on('people')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('person_contact_type_id', 'person_contact_ibfk_2')->references('id')->on('person_contact_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('person_contact', function(Blueprint $table)
		{
			$table->dropForeign('person_contact_ibfk_1');
			$table->dropForeign('person_contact_ibfk_2');
		});
	}

}
