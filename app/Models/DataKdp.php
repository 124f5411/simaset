<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKdp extends Model
{
    use HasFactory;
    protected $table = 'data_konstruksi';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
