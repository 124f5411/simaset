<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPeralatan extends Model
{
    use HasFactory;
    protected $table = 'data_peralatan';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
