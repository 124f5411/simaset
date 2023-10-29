<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKontrak extends Model
{
    use HasFactory;
    protected $table = 'data_kontrak';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
