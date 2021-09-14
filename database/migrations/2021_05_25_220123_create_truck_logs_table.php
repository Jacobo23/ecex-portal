<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('truck_location_id');
            $table->string('position')->default("");
            $table->dateTime('date_in', $precision = 0);
            $table->dateTime('date_out', $precision = 0);
            $table->unsignedBigInteger('customer_id');
            $table->string('trailer')->default("");
            $table->boolean('loaded')->default(true);
            $table->string('observations',512)->default("");
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
        Schema::dropIfExists('truck_logs');
    }
}
