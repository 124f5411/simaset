<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKibB extends Model
{
    use HasFactory;
    protected $table = 'kib_b';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
