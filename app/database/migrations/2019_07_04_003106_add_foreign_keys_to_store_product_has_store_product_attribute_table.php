<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStoreProductHasStoreProductAttributeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('store_product_has_store_product_attribute', function(Blueprint $table)
		{
			$table->foreign('store_product_id', 'store_product_has_store_product_attribute_ibfk_1')->references('id')->on('store_product')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('store_product_attribute_id', 'store_product_has_store_product_attribute_ibfk_2')->references('id')->on('store_product_attribute')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('store_product_has_store_product_attribute', function(Blueprint $table)
		{
			$table->dropForeign('store_product_has_store_product_attribute_ibfk_1');
			$table->dropForeign('store_product_has_store_product_attribute_ibfk_2');
		});
	}

}
