<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonContactTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('person_contact', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->nullable()->index('company_id');
			$table->integer('person_id')->unsigned()->index('person_id');
			$table->integer('person_contact_type_id')->unsigned()->index('person_contact_type_id');
			$table->string('name', 100)->nullable();
			$table->string('value', 100)->default('');
			$table->string('description')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('source_id')->unsigned()->nullable();
			$table->timestamp('synced_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('person_contact');
	}

}
