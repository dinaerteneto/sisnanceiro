<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->integer('customer_id')->unsigned()->nullable()->index('customer_id');
			$table->integer('user_id_created')->unsigned()->index('user_id_created');
			$table->integer('user_id_deleted')->unsigned()->nullable()->index('user_id_deleted');
			$table->integer('payment_method_id_fine_value')->unsigned()->nullable()->index('fine_value_payment_method_id');
			$table->integer('company_sale_code')->unsigned();
			$table->integer('status')->nullable();
			$table->decimal('gross_value', 10)->default(0.00);
			$table->decimal('discount_value', 10)->nullable()->default(0.00);
			$table->decimal('net_value', 10)->default(0.00);
			$table->text('fine_cancel_reason', 65535)->nullable();
			$table->decimal('fine_cancel_value', 10)->default(0.00);
			$table->date('sale_date');
			$table->date('cancel_date')->nullable();
			$table->dateTime('canceled_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('source_id')->unsigned()->nullable();
			$table->timestamp('synced_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->unique(['company_sale_code','company_id'], 'company_sale_code');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sale');
	}

}
