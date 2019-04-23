<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPersonAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('person_address', function(Blueprint $table)
		{
			$table->foreign('person_id', 'person_address_ibfk_1')->references('id')->on('people')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('person_address_type_id', 'person_address_ibfk_2')->references('id')->on('person_address_types')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('person_address', function(Blueprint $table)
		{
			$table->dropForeign('person_address_ibfk_1');
			$table->dropForeign('person_address_ibfk_2');
		});
	}

}
