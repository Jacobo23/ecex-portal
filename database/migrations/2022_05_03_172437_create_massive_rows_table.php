<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMassiveRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('massive_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_id');
            $table->string('part_number_name')->default("");
            $table->string('part_number_id')->default("");
            $table->string('desc_ing')->default("");
            $table->string('desc_esp')->default("");
            $table->string('origin_country')->default("");
            $table->string('units')->default("");
            $table->string('um')->default("");
            $table->string('bundles')->default("");
            $table->string('bundle_type')->default("");
            $table->string('net_weight')->default("");
            $table->string('gross_weight')->default("");
            $table->string('fraccion')->default("");
            $table->string('nico')->default("");
            $table->string('po')->default("");
            $table->string('brand')->default("");
            $table->string('model')->default("");
            $table->string('serial')->default("");
            $table->string('location')->default("");
            $table->string('regime')->default("");
            $table->string('lot')->default("");
            $table->string('skids')->default("");
            $table->string('imex')->default("");
            $table->string('observations')->default("");
            $table->string('validation')->default("");
            $table->string('validation_msg')->default("");
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
        Schema::dropIfExists('massive_rows');
    }
}
