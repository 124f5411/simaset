<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataKonstruksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_konstruksi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opd');
            $table->string('kode');
            $table->string('register');
            $table->enum('bangunan',['P','SP','D']);
            $table->bigInteger('id_kontrak');
            $table->boolean('tingkat')->default(0);
            $table->boolean('beton')->default(0);
            $table->string('luas')->nullable();
            $table->string('no_dokumen')->nullable();
            $table->date('tgl_dokumen')->nullable();
            $table->longText('alamat')->nullable();
            $table->string('kode_tanah')->nullable();
            $table->bigInteger('id_status_tanah')->nullable();
            $table->string('asal')->nullable();
            $table->date('t_mulai')->nullable();
            $table->string('harga')->nullable();
            $table->longText('keterangan')->nullable();
            $table->enum('status',['0','1','2']);
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
        Schema::dropIfExists('data_konstruksi');
    }
}
