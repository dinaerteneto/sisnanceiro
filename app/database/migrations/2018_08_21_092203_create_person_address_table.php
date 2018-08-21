<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('person_address', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->index('person_id');
			$table->integer('person_address_type_id')->unsigned()->index('person_address_type_id');
			$table->string('name', 100)->nullable();
			$table->string('zip_code', 20)->nullable();
			$table->string('address', 100)->nullable();
			$table->string('number', 100)->nullable();
			$table->string('complement', 100)->nullable();
			$table->string('reference', 100)->nullable();
			$table->string('city', 100)->nullable();
			$table->string('district', 100)->nullable();
			$table->string('uf', 100)->nullable();
			$table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
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
		Schema::drop('person_address');
	}

}
