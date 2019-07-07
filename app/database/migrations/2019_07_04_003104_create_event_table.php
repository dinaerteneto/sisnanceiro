<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->string('name', 100);
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->integer('people_limit')->nullable();
			$table->integer('guest_limit_per_person')->nullable();
			$table->integer('days_for_cancel')->nullable();
			$table->float('value_per_person')->nullable();
			$table->string('phone')->nullable();
			$table->string('whatsapp')->nullable();
			$table->boolean('accept_money_payment')->nullable();
			$table->string('email')->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('zipcode', 9)->nullable();
			$table->string('address')->nullable();
			$table->integer('address_number')->nullable();
			$table->string('city')->nullable();
			$table->string('district')->nullable();
			$table->string('uf', 2)->nullable();
			$table->string('complement')->nullable();
			$table->string('reference')->nullable();
			$table->string('latitude')->nullable();
			$table->integer('longitude')->nullable();
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
		Schema::drop('event');
	}

}
