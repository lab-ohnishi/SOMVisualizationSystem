<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Perspectives extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perspectives', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('event_id')->unsigned();
				$table->integer('from_id')->unsigned();
				$table->integer('to_id')->unsigned();
				$table->integer('pers01');
				$table->integer('pers02');
				$table->integer('pers03');
				$table->integer('pers04');
				$table->integer('pers05');
				$table->integer('pers06');
				$table->integer('pers07');
				$table->foreign('event_id')->references('id')->on('events');
				$table->foreign('from_id')->references('id')->on('users');
				$table->foreign('to_id')->references('id')->on('users');
				
				});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('perspectives');
	}
}
