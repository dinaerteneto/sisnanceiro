<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventGuestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_guest', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->integer('event_id')->unsigned()->index('event_id');
			$table->integer('person_id')->unsigned()->nullable()->index('person_id');
			$table->integer('invited_by_id')->unsigned()->nullable()->index('invited_by_id');
			$table->integer('payment_method_id')->unsigned()->nullable()->index('payment_method_id');
			$table->integer('responsable_of_payment')->unsigned()->nullable()->index('responsable_of_payment');
			$table->string('email')->default('');
			$table->string('person_name')->nullable();
			$table->string('token_email')->nullable();
			$table->float('value')->nullable();
			$table->integer('status')->nullable();
			$table->string('whatsapp', 100)->nullable();
			$table->string('student_name')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->unique(['email','event_id'], 'email');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('event_guest');
	}

}
