<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreProductHasStoreProductAttributeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store_product_has_store_product_attribute', function(Blueprint $table)
		{
			$table->integer('store_product_id')->unsigned();
			$table->integer('store_product_attribute_id')->unsigned()->index('store_product_attribute_id');
			$table->string('value', 100)->default('');
			$table->timestamps();
			$table->softDeletes();
			$table->primary(['store_product_id','store_product_attribute_id']);
			$table->unique(['store_product_id','store_product_attribute_id','value'], 'unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('store_product_has_store_product_attribute');
	}

}
