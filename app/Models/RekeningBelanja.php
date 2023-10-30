<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningBelanja extends Model
{
    use HasFactory;
    protected $table = 'referensi_rekening_belanja';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
