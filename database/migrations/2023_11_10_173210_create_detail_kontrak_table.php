<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailKontrakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('register');
            $table->bigInteger('id_kontrak')->nullable();
            $table->string('luas_tanah')->nullable();
            $table->year('tahun')->nullable();
            $table->longText('alamat')->nullable();
            $table->bigInteger('id_hak')->nullable();
            $table->date('tgl_sertifikat')->nullable();
            $table->string('no_sertifikat')->nullable();
            $table->string('penggunaan')->nullable();
            $table->string('merek')->nullable();
            $table->string('spesifikasi')->nullable();
            $table->string('bahan')->nullable();
            $table->string('pabrik')->nullable();
            $table->string('no_rangka')->nullable();
            $table->date('no_mesin')->nullable();
            $table->string('nopol')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('luas_lantai')->nullable();
            $table->string('no_dokumen')->nullable();
            $table->date('tgl_dokumen')->nullable();
            $table->string('kode_tanah')->nullable();
            $table->string('konstruksi')->nullable();
            $table->string('panjang')->nullable();
            $table->string('lebar')->nullable();
            $table->string('nm_aset')->nullable();
            $table->string('asal_daerah')->nullable();
            $table->string('pencipta')->nullable();
            $table->string('jenis')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('jumlah')->nullable();
            $table->enum('bangunan',['P','SP','D'])->nullable();
            $table->boolean('tingkat')->nullable();
            $table->boolean('beton')->nullable();
            $table->date('t_mulai')->nullable();
            $table->bigInteger('id_status_tanah')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('asal')->nullable();
            $table->string('harga')->nullable();
            $table->longText('keterangan')->nullable();
            $table->enum('status',['0','1','2'])->default('0');
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
        Schema::dropIfExists('detail_kontrak');
    }
}
