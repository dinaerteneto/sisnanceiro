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
			$table->foreign('person_contact_type_id', 'person_contact_ibfk_2')->references('id')->on('person_contact_type')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('company_id', 'person_contact_ibfk_3')->references('id')->on('company')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('person_id', 'person_contact_ibfk_4')->references('id')->on('person')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
			$table->dropForeign('person_contact_ibfk_2');
			$table->dropForeign('person_contact_ibfk_3');
			$table->dropForeign('person_contact_ibfk_4');
		});
	}

}
