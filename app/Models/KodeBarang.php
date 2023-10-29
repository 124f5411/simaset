<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeBarang extends Model
{
    use HasFactory;
    protected $table = 'referensi_kode_barang';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
