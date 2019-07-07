<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonContactTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('person_contact_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->nullable()->index('company_id');
			$table->string('type', 100)->default('');
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
		Schema::drop('person_contact_type');
	}

}
