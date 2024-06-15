<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_status', function (Blueprint $table) {
            $table->id('ID');
            $table->integer('TableID')->default(0);
            $table->integer('OrderID')->default(0);
            $table->dateTime('Reserve')->default('1911-01-01 00:00:00');
            $table->integer('Status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_status');
    }
}
