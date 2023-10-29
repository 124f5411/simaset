<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTanah extends Model
{
    use HasFactory;
    protected $table = 'data_tanah';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
