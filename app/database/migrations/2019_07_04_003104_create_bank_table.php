<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('ID');
			$table->string('code', 10)->nullable()->comment('Code');
			$table->string('title', 180)->comment('Title');
			$table->string('document', 180)->nullable()->comment('Document');
			$table->string('site', 180)->nullable()->comment('Site');
			$table->enum('status', array('0','1'))->default('1')->comment('Status');
			$table->timestamps();
			$table->softDeletes()->comment('Deleted At');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bank');
	}

}
