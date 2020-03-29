<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateUzytkownikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uzytkownik', function (Blueprint $table) {
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
        Schema::dropIfExists('uzytkownik');
    }
}