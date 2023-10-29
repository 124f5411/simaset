<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKibF extends Model
{
    use HasFactory;
    protected $table = 'kib_f';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
