<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakTanah extends Model
{
    use HasFactory;
    protected $table = 'jns_hak';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
