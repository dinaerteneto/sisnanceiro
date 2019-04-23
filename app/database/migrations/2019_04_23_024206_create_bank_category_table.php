<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_category', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->nullable()->index('company_id');
			$table->integer('main_parent_category_id')->unsigned()->nullable()->index('main_parent_category_id');
			$table->integer('parent_category_id')->unsigned()->nullable()->index('parent_category_id');
			$table->string('name', 45);
			$table->integer('status')->default(1)->comment('1 - active
2 - inative
3 - cannot delete');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bank_category');
	}

}
