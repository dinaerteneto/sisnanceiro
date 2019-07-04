<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreProductStockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store_product_stock', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->integer('store_product_id')->unsigned()->index('store_product_id');
			$table->integer('sale_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable()->index('user_id');
			$table->integer('store_product_stock_reason_id')->unsigned()->nullable()->index('store_product_stock_reason_id');
			$table->integer('total')->default(0);
			$table->text('description', 65535);
			$table->dateTime('created_at');
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
		Schema::drop('store_product_stock');
	}

}
