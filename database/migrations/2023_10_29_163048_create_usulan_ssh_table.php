<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsulanSshTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usulan_ssh', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_opd');
            $table->year('tahun');
            $table->enum('induk_perubahan',['1','2'])->nullable();
            $table->string('ssd_dokumen')->nullable();
            $table->bigInteger('id_kelompok');
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
        Schema::dropIfExists('usulan_ssh');
    }
}
