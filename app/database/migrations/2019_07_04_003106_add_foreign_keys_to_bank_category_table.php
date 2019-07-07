<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBankCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bank_category', function(Blueprint $table)
		{
			$table->foreign('main_parent_category_id', 'bank_category_ibfk_1')->references('id')->on('bank_category')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('parent_category_id', 'bank_category_ibfk_2')->references('id')->on('bank_category')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('company_id', 'bank_category_ibfk_3')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bank_category', function(Blueprint $table)
		{
			$table->dropForeign('bank_category_ibfk_1');
			$table->dropForeign('bank_category_ibfk_2');
			$table->dropForeign('bank_category_ibfk_3');
		});
	}

}
