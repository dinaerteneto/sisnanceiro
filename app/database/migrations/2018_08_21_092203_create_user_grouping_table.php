<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserGroupingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_grouping', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->integer('user_group_id')->unsigned()->index('user_group_id');
			$table->primary(['user_id','user_group_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_grouping');
	}

}
