<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('number');
            $table->string('regime');
            $table->dateTime('cdate', $precision = 0);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('carrier_id');
            $table->string('trailer')->default("");
            $table->string('seal')->default("");
            $table->string('observations',1024)->default("");
            $table->string('invoice')->default("");
            $table->string('pediment')->default("");
            $table->string('reference')->default("");
            $table->string('user')->default("");
            $table->string('received_by')->default("");
            $table->string('plate')->default("");
            $table->dateTime('leave', $precision = 0);
            $table->boolean('discount')->default(false);
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
        Schema::dropIfExists('outcomes');
    }
}
