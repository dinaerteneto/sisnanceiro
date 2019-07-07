<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStoreProductCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('store_product_category', function(Blueprint $table)
		{
			$table->foreign('company_id', 'store_product_category_ibfk_1')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('store_product_category', function(Blueprint $table)
		{
			$table->dropForeign('store_product_category_ibfk_1');
		});
	}

}
