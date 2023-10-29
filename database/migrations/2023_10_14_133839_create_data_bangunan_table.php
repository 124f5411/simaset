<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataBangunanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_bangunan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opd');
            $table->string('kode');
            $table->string('register');
            $table->bigInteger('id_jenis');
            $table->bigInteger('id_kontrak');
            $table->boolean('tingkat')->default(0);
            $table->boolean('beton')->default(0);
            $table->string('luas_lantai')->nullable();
            $table->string('no_dokumen')->nullable();
            $table->date('tgl_dokumen')->nullable();
            $table->string('luas_tanah')->nullable();
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
        Schema::dropIfExists('data_bangunan');
    }
}
