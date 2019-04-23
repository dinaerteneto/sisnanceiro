<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserGroupingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_grouping', function(Blueprint $table)
		{
			$table->foreign('user_id', 'user_grouping_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_group_id', 'user_grouping_ibfk_2')->references('id')->on('user_group')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_grouping', function(Blueprint $table)
		{
			$table->dropForeign('user_grouping_ibfk_1');
			$table->dropForeign('user_grouping_ibfk_2');
		});
	}

}
