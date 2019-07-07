<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSupplierTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('supplier', function(Blueprint $table)
		{
			$table->foreign('id', 'supplier_ibfk_1')->references('id')->on('person')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('supplier', function(Blueprint $table)
		{
			$table->dropForeign('supplier_ibfk_1');
		});
	}

}
