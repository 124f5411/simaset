<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataPeralatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_peralatan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opd');
            $table->string('kode');
            $table->string('register');
            // $table->string('nm_peralatan');
            $table->bigInteger('id_kontrak');
            $table->string('merek')->nullable();
            $table->string('spek')->nullable();
            $table->string('bahan')->nullable();
            $table->year('tahun')->nullable();
            $table->string('pabrik')->nullable();
            $table->string('no_rangka')->nullable();
            $table->date('no_mesin')->nullable();
            $table->string('nopol')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('asal')->nullable();
            $table->string('harga')->nullable();
            // $table->string('jumlah')->nullable();
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
        Schema::dropIfExists('data_peralatan');
    }
}
