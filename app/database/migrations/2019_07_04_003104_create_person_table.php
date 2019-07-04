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
			$table->string('firstname')->default('');
			$table->string('lastname')->nullable()->default('');
			$table->string('cpf')->nullable();
			$table->string('rg')->nullable();
			$table->string('email')->nullable();
			$table->date('birthdate')->nullable();
			$table->char('gender', 1)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('source_id')->unsigned()->nullable();
			$table->timestamp('synced_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('status')->nullable();
			$table->string('company_name', 100)->nullable();
			$table->unique(['email','company_id'], 'email');
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
