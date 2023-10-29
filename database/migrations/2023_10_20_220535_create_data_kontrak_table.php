<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataKontrakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('no_kontrak');
            $table->string('nm_kontrak');
            $table->year('tahun');
            $table->date('t_kontrak');
            $table->bigInteger('opd');
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
        Schema::dropIfExists('data_kontrak');
    }
}
