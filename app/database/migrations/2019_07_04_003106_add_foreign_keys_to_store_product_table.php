<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStoreProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('store_product', function(Blueprint $table)
		{
			$table->foreign('company_id', 'store_product_ibfk_1')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('store_product_brand_id', 'store_product_ibfk_2')->references('id')->on('store_product_brand')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('store_product_category_id', 'store_product_ibfk_3')->references('id')->on('store_product_category')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('store_product_id', 'store_product_ibfk_4')->references('id')->on('store_product')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('bank_category_id', 'store_product_ibfk_5')->references('id')->on('bank_category')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('store_product', function(Blueprint $table)
		{
			$table->dropForeign('store_product_ibfk_1');
			$table->dropForeign('store_product_ibfk_2');
			$table->dropForeign('store_product_ibfk_3');
			$table->dropForeign('store_product_ibfk_4');
			$table->dropForeign('store_product_ibfk_5');
		});
	}

}
