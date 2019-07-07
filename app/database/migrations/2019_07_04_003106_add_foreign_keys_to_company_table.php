<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company', function(Blueprint $table)
		{
			$table->foreign('id', 'company_ibfk_1')->references('id')->on('person')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company', function(Blueprint $table)
		{
			$table->dropForeign('company_ibfk_1');
		});
	}

}
