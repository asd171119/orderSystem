<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function (Blueprint $table) {
			$table->id('ID');
			$table->string('Token')->default('');
			$table->string('Name')->default('');
			$table->integer('Price')->default(0);
			$table->integer('OnSale')->default(0);
			$table->integer('Status')->default(0);
			$table->string('Image')->default('');
			$table->timestamps();
			$table->integer('IsDeleted')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('menu');
	}
}
