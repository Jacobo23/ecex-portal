<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIncomeRowsRegimeAndPackingId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_rows', function (Blueprint $table) {
            $table->renameColumn('extras', 'regime');
            $table->renameColumn('packing_id', 'skids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_rows', function (Blueprint $table) {
            $table->renameColumn('regime', 'extras');
            $table->renameColumn('skids', 'packing_id');
        });
    }
}
