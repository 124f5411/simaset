<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataJalanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_jalan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opd');
            $table->string('kode');
            $table->string('register');
            $table->bigInteger('id_jenis');
            $table->bigInteger('id_kontrak');
            $table->string('konstruksi')->nullable();
            $table->string('panjang')->nullable();
            $table->string('lebar')->nullable();
            $table->string('luas')->nullable();
            $table->longText('alamat')->nullable();
            $table->date('tgl_dokumen')->nullable();
            $table->string('no_dokumen')->nullable();
            $table->string('kode_tanah')->nullable();
            $table->bigInteger('id_status_tanah')->nullable();
            $table->string('asal')->nullable();
            $table->string('harga')->nullable();
            $table->string('kondisi')->nullable();
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
        Schema::dropIfExists('data_jalan');
    }
}
