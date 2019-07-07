<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankInvoiceTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_invoice_transaction', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->integer('bank_category_id')->unsigned()->nullable()->index('bank_category_id');
			$table->integer('total_invoices');
			$table->decimal('total_value');
			$table->integer('type_cycle')->nullable();
			$table->text('description', 65535)->nullable();
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
		Schema::drop('bank_invoice_transaction');
	}

}
