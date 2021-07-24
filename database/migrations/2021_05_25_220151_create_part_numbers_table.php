<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('part_number');
            $table->unsignedBigInteger('customer_id');
            $table->string('um');
            $table->decimal('unit_weight', 8, 2);
            $table->string('desc_ing')->default("");
            $table->string('desc_esp')->default("");
            $table->string('origin_country')->default("");
            $table->string('fraccion')->default("");
            $table->string('nico')->default("");
            $table->string('brand')->default("");
            $table->string('model')->default("");
            $table->string('serial')->default("");
            $table->string('imex')->default("");
            $table->string('fraccion_especial')->default("");
            $table->string('regime')->default("");
            $table->boolean('warning')->default(false);
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
        Schema::dropIfExists('part_numbers');
    }
}
