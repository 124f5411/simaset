<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKib extends Model
{
    use HasFactory;
    protected $table = 'kib_master';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
