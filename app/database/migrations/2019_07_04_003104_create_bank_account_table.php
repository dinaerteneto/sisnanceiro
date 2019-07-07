<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_account', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->integer('bank_id')->nullable()->index('bank_id');
			$table->boolean('default')->default(0);
			$table->boolean('default_online_transaction')->nullable();
			$table->boolean('physical')->nullable()->default(0);
			$table->string('name', 45);
			$table->string('bank', 45)->nullable();
			$table->string('agency', 45)->nullable();
			$table->string('account', 45)->nullable();
			$table->string('type', 50)->nullable();
			$table->decimal('initial_balance')->nullable()->default(0.00);
			$table->date('initial_balance_date')->nullable();
			$table->string('agency_dv', 1)->nullable();
			$table->string('account_dv', 1)->nullable();
			$table->string('cpf_cnpj', 45)->nullable();
			$table->string('legal_name', 30)->nullable();
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
		Schema::drop('bank_account');
	}

}
