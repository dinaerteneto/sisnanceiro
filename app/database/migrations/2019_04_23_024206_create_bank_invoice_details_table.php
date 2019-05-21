<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankInvoiceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_invoice_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->unsigned()->nullable()->index('parent_id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->integer('customer_id')->unsigned()->nullable()->index('customer_id');
			$table->integer('supplier_id')->unsigned()->nullable();
			$table->integer('bank_category_id')->unsigned()->index('bank_category_id');
			$table->integer('bank_account_id')->unsigned()->index('bank_account_id');
			$table->integer('bank_invoice_transaction_id')->unsigned()->nullable()->index('bank_invoice_transaction_id');
			$table->integer('payment_method_id')->unsigned()->nullable()->index('payment_method_id');
			$table->integer('payment_tax_term_id')->unsigned()->nullable()->index('payment_tax_term_id');
			$table->decimal('gross_value');
			$table->decimal('discount_value')->nullable();
			$table->decimal('tax_value')->nullable()->default(0.00);
			$table->decimal('rate_value')->nullable()->default(0.00);
			$table->decimal('net_value');
			$table->integer('parcel_number')->nullable()->default(0);
			$table->date('competence_date');
			$table->date('due_date')->nullable();
			$table->integer('due_day')->nullable();
			$table->date('payment_date')->nullable();
			$table->date('receive_date')->nullable();
			$table->dateTime('receive_date_availabre')->nullable();
			$table->text('note', 65535)->nullable();
			$table->integer('status')->default(1);
			$table->text('status_reason', 65535)->nullable();
			$table->decimal('payment_tax_percent')->nullable();
			$table->string('gateway_transaction')->nullable();
			$table->integer('grouped')->nullable();
			$table->string('authorization_code')->nullable();
			$table->boolean('hide')->nullable();
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
		Schema::drop('bank_invoice_details');
	}

}
