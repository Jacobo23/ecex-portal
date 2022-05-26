<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_id');
            $table->unsignedBigInteger('part_number_id');
            $table->decimal('units', 16, 4)->default(0);
            $table->integer('bundles')->default(0);
            $table->string('umb');
            $table->string('ump');
            $table->decimal('net_weight', 16, 4)->default(0);
            $table->decimal('gross_weight', 16, 4)->default(0);
            $table->string('po')->default("");
            $table->string('desc_ing')->default("");
            $table->string('desc_esp')->default("");
            $table->string('origin_country')->default("");
            $table->string('fraccion')->default("");
            $table->string('nico')->default("");
            $table->string('location')->default("");
            $table->string('observations',1024)->default("");
            $table->string('regime')->default("");
            $table->string('brand')->default("");
            $table->string('model')->default("");
            $table->string('serial')->default("");
            $table->string('lot')->default("");
            $table->string('skids')->default("");
            $table->string('imex')->default("");
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
        Schema::dropIfExists('income_rows');
    }
}
