<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefBarangKontrakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_barang_kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->smallInteger('masa')->nullable();
            $table->string('batas')->nullable();
            $table->string('kib',1)->nullable();
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
        Schema::dropIfExists('ref_barang_kontrak');
    }
}
