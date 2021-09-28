<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadOrderRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_order_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('load_order_id');
            $table->unsignedBigInteger('income_row_id');
            $table->decimal('units', 16, 4)->default(0);
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
        Schema::dropIfExists('load_order_rows');
    }
}
