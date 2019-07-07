<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSaleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sale', function(Blueprint $table)
		{
			$table->foreign('company_id', 'sale_ibfk_1')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('customer_id', 'sale_ibfk_2')->references('id')->on('customer')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id_created', 'sale_ibfk_3')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id_deleted', 'sale_ibfk_4')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('payment_method_id_fine_value', 'sale_ibfk_5')->references('id')->on('payment_method')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sale', function(Blueprint $table)
		{
			$table->dropForeign('sale_ibfk_1');
			$table->dropForeign('sale_ibfk_2');
			$table->dropForeign('sale_ibfk_3');
			$table->dropForeign('sale_ibfk_4');
			$table->dropForeign('sale_ibfk_5');
		});
	}

}
