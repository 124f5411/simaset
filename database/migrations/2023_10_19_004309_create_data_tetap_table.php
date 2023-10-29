<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTetapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_tetap', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opd');
            $table->string('kode');
            $table->string('register');
            $table->bigInteger('id_jenis');
            $table->bigInteger('id_kontrak');
            $table->string('judul')->nullable();
            $table->string('spesifikasi')->nullable();
            $table->string('asal_daerah')->nullable();
            $table->string('pencipta')->nullable();
            $table->string('bahan')->nullable();
            $table->string('jenis')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('asal')->nullable();
            $table->string('tahun')->nullable();
            $table->string('harga')->nullable();
            $table->longText('keterangan')->nullable();
            $table->enum('status',['0','1','2','3']);
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
        Schema::dropIfExists('data_tetap');
    }
}
