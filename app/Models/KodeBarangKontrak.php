<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeBarangKontrak extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'ref_barang_kontrak';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
