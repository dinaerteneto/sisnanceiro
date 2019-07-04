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
			$table->foreign('person_address_type_id', 'person_address_ibfk_2')->references('id')->on('person_address_type')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('company_id', 'person_address_ibfk_3')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('person_id', 'person_address_ibfk_4')->references('id')->on('person')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
			$table->dropForeign('person_address_ibfk_2');
			$table->dropForeign('person_address_ibfk_3');
			$table->dropForeign('person_address_ibfk_4');
		});
	}

}
