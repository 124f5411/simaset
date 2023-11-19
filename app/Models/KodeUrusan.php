<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeUrusan extends Model
{
    use HasFactory;
    protected $table = 'kode_urusan';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
