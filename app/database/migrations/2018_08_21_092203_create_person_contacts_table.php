<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('person_contacts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->index('person_id');
			$table->integer('person_contact_type_id')->unsigned()->index('person_contact_type_id');
			$table->string('value', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('person_contacts');
	}

}
