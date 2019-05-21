<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('person', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->nullable()->index('company_id');
			$table->char('physical', 1)->nullable();
			$table->string('name');
			$table->string('last_name')->nullable()->default('');
			$table->string('cpf')->nullable();
			$table->string('rg')->nullable();
			$table->date('birthday')->nullable();
			$table->char('gender', 1)->nullable();
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
		Schema::drop('person');
	}

}
