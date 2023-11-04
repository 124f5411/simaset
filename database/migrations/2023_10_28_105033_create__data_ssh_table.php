<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataSshTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_data_ssh', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_kode');
            $table->bigInteger('id_usulan');
            $table->bigInteger('id_kelompok')->nullable();
            $table->string('spesifikasi');
            $table->string('uraian')->nullable();
            $table->bigInteger('id_satuan');
            $table->string('harga');
            $table->enum('status',['0','1','2']);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('_data_ssh');
    }
}
