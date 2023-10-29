<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanSsh extends Model
{
    use HasFactory;
    protected $table = 'usulan_ssh';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
