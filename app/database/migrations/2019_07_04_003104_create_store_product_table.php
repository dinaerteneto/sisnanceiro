<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store_product', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index('company_id');
			$table->integer('store_product_brand_id')->unsigned()->nullable()->index('store_product_brand_id');
			$table->integer('store_product_category_id')->unsigned()->nullable()->index('store_product_category_id');
			$table->integer('store_product_id')->unsigned()->nullable()->index('store_product_id');
			$table->integer('bank_category_id')->unsigned()->nullable()->index('bank_category_id');
			$table->string('name', 100)->nullable();
			$table->string('sku', 45)->nullable();
			$table->decimal('price')->nullable();
			$table->decimal('weight', 8, 3)->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('total_in_stock')->default(0);
			$table->boolean('sale_with_negative_stock')->nullable();
			$table->boolean('status')->nullable()->default(1);
			$table->timestamps();
			$table->softDeletes();
			$table->integer('source_id')->unsigned()->nullable();
			$table->timestamp('synced_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('unit_measurement', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('store_product');
	}

}
