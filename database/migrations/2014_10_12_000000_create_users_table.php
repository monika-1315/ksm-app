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
            $table->bigIncrements('id');
            $table->string('email',50);
            $table->string('password',100);
            $table->string('name',30);
            $table->string('surname',50);
            $table->date('birthdate');
            $table->integer('division');
            $table->boolean('is_authorized');
            $table->boolean('is_leadership');
            $table->boolean('is_management');
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
