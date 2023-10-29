<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTanah extends Model
{
    use HasFactory;
    protected $table = 'status_tanah';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
