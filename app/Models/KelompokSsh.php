<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokSsh extends Model
{
    use HasFactory;
    protected $table = '_kelompok_ssh';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
