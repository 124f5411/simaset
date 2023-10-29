<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKibA extends Model
{
    use HasFactory;
    protected $table = 'kib_a';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
