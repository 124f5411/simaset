<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRincianUsulan extends Model
{
    use HasFactory;
    protected $table = 'detail_usulan';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
