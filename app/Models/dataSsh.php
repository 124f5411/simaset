<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dataSsh extends Model
{
    use HasFactory;
    protected $table = '_data_ssh';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
