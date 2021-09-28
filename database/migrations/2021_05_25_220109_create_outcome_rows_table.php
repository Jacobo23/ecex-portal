<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcomeRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcome_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outcome_id');
            $table->unsignedBigInteger('income_row_id');
            $table->decimal('units', 16, 4)->default(0);
            $table->integer('bundles')->default(0);
            $table->string('ump');
            $table->string('umb');
            $table->decimal('net_weight', 16, 4)->default(0);
            $table->decimal('gross_weight', 16, 4)->default(0);
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
        Schema::dropIfExists('outcome_rows');
    }
}
