<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKontrak extends Model
{
    use HasFactory;
    protected $table = 'detail_kontrak';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
