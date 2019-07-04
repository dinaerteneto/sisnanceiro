<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankInvoiceDetailChecksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_invoice_detail_checks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bank_invoice_detail_id')->unsigned()->index('bank_invoice_detail_id');
			$table->integer('bank_id')->nullable()->index('bank_id');
			$table->string('holder')->nullable()->default('');
			$table->string('bank')->nullable()->default('');
			$table->string('agency', 45)->nullable()->default('');
			$table->string('account', 45)->nullable()->default('');
			$table->string('number', 45)->nullable()->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bank_invoice_detail_checks');
	}

}
