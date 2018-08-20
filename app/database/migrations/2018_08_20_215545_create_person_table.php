<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('last_name')->nullable();
            $table->boolean('physical');
            $table->enum('gender', ['m', 'f']);
            $table->string('status')->nullable(false);
            $table->timestamps();
            $table->softDeletes();    
        });

        Schema::table('people', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('person_id')
                ->references('id')->on('people')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
        });          
        
        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });          
        Schema::dropIfExists('people');
    }
}
