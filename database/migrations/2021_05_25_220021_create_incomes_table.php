<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('number');
            $table->dateTime('cdate', $precision = 0);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('carrier_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('reference')->default("");
            $table->string('trailer')->default("");
            $table->string('seal')->default("");
            $table->string('observations',1024)->default("");
            $table->string('impoExpo')->default("");
            $table->string('invoice')->default("");
            $table->string('tracking')->default("");
            $table->string('po')->default("");
            $table->boolean('sent')->default(false);
            $table->string('user')->default("");
            $table->boolean('reviewed')->default(false);
            $table->string('reviewed_by')->default("");
            $table->boolean('closed')->default(false);
            $table->boolean('urgent')->default(false);
            $table->boolean('onhold')->default(false);
            $table->string('type')->default("");
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
        Schema::dropIfExists('incomes');
    }
}
