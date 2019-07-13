<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBankInvoiceDetailChecksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bank_invoice_detail_checks', function(Blueprint $table)
		{
			$table->foreign('bank_invoice_detail_id', 'bank_invoice_detail_checks_ibfk_1')->references('id')->on('bank_invoice_detail')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('bank_id', 'bank_invoice_detail_checks_ibfk_2')->references('id')->on('bank')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bank_invoice_detail_checks', function(Blueprint $table)
		{
			$table->dropForeign('bank_invoice_detail_checks_ibfk_1');
			$table->dropForeign('bank_invoice_detail_checks_ibfk_2');
		});
	}

}
