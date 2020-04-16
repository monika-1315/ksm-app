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
            $table->bigIncrements('Id');
            $table->string('Email',50);
            $table->string('Haslo',100);
            $table->string('Imie',30);
            $table->string('Nazwisko',50);
            $table->date('DataUr');
            $table->integer('OddzialId');
            $table->boolean('CzyZautoryzowane');
            $table->boolean('CzyKierownictwo');
            $table->boolean('CzyZarzad');
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
