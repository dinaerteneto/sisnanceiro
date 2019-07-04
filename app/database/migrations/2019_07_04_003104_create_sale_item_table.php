<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->nullable();
			$table->integer('sale_id')->unsigned();
			$table->integer('store_product_id')->unsigned();
			$table->decimal('unit_value', 10);
			$table->decimal('quantity', 8, 3);
			$table->string('discount_type', 2)->nullable();
			$table->decimal('discount_value', 10);
			$table->text('discount_reason', 65535)->nullable();
			$table->decimal('total_value', 10);
			$table->timestamps();
			$table->softDeletes();
			$table->integer('source_id')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sale_item');
	}

}
