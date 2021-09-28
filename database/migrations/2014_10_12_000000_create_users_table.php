<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('none');//user or customer
            $table->string('name')->unique();
            $table->string('email')->default('');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('');
            $table->string('permits')->default('');
            $table->string('customer_ids')->nullable(); //comma separated customer ids
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
