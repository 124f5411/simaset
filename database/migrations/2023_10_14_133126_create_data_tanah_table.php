<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTanahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_tanah', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opd');
            $table->string('kode');
            $table->string('register');
            // $table->bigInteger('id_jenis');
            $table->bigInteger('id_kontrak')->nullable();
            $table->string('luas')->nullable();
            $table->year('tahun')->nullable();
            $table->longText('alamat')->nullable();
            $table->bigInteger('id_hak')->nullable();
            $table->date('tgl_sertifikat')->nullable();
            $table->string('no_sertifikat')->nullable();
            $table->string('penggunaan')->nullable();
            $table->string('asal')->nullable();
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
        Schema::dropIfExists('data_tanah');
    }
}
