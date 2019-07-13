<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBankInvoiceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bank_invoice_detail', function(Blueprint $table)
		{
			$table->foreign('parent_id', 'bank_invoice_detail_ibfk_1')->references('id')->on('bank_invoice_detail')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('company_id', 'bank_invoice_detail_ibfk_10')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('customer_id', 'bank_invoice_detail_ibfk_11')->references('id')->on('customer')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('bank_category_id', 'bank_invoice_detail_ibfk_6')->references('id')->on('bank_category')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('bank_account_id', 'bank_invoice_detail_ibfk_7')->references('id')->on('bank_account')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('payment_method_id', 'bank_invoice_detail_ibfk_8')->references('id')->on('payment_method')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('bank_invoice_transaction_id', 'bank_invoice_detail_ibfk_9')->references('id')->on('bank_invoice_transaction')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bank_invoice_detail', function(Blueprint $table)
		{
			$table->dropForeign('bank_invoice_detail_ibfk_1');
			$table->dropForeign('bank_invoice_detail_ibfk_10');
			$table->dropForeign('bank_invoice_detail_ibfk_11');
			$table->dropForeign('bank_invoice_detail_ibfk_6');
			$table->dropForeign('bank_invoice_detail_ibfk_7');
			$table->dropForeign('bank_invoice_detail_ibfk_8');
			$table->dropForeign('bank_invoice_detail_ibfk_9');
		});
	}

}
