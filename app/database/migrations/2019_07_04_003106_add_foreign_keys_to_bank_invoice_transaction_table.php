<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBankInvoiceTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bank_invoice_transaction', function(Blueprint $table)
		{
			$table->foreign('bank_category_id', 'bank_invoice_transaction_ibfk_2')->references('id')->on('bank_category')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('company_id', 'bank_invoice_transaction_ibfk_3')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bank_invoice_transaction', function(Blueprint $table)
		{
			$table->dropForeign('bank_invoice_transaction_ibfk_2');
			$table->dropForeign('bank_invoice_transaction_ibfk_3');
		});
	}

}
