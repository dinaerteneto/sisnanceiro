<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_grouping', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('user_group_id');
        });

        Schema::table('user_grouping', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('user_group_id')
                ->references('id')->on('user_groups')
                ->onUpdate('cascade')
                ->onDelete('restrict');
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_grouping', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });           
        Schema::table('user_grouping', function (Blueprint $table) {
            $table->dropForeign(['user_group_id']);
        });           
        Schema::dropIfExists('user_grouping');
    }
}
