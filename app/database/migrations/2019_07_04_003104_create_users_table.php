<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->string('email', 100)->default('');
			$table->string('password')->default('');
			$table->string('remember_token', 100)->default('');
			$table->timestamps();
			$table->integer('source_id')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
