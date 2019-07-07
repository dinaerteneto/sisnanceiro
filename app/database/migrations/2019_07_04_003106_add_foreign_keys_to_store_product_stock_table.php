<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStoreProductStockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('store_product_stock', function(Blueprint $table)
		{
			$table->foreign('company_id', 'store_product_stock_ibfk_1')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('store_product_id', 'store_product_stock_ibfk_2')->references('id')->on('store_product')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id', 'store_product_stock_ibfk_3')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('store_product_stock_reason_id', 'store_product_stock_ibfk_4')->references('id')->on('store_product_stock_reason')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('store_product_stock', function(Blueprint $table)
		{
			$table->dropForeign('store_product_stock_ibfk_1');
			$table->dropForeign('store_product_stock_ibfk_2');
			$table->dropForeign('store_product_stock_ibfk_3');
			$table->dropForeign('store_product_stock_ibfk_4');
		});
	}

}
