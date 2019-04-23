<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankInvoiceDetailStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_invoice_detail_status', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100)->nullable();
			$table->string('label', 100)->nullable();
			$table->string('type', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bank_invoice_detail_status');
	}

}
