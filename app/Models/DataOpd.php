<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOpd extends Model
{
    use HasFactory;
    protected $table = 'data_opd';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
