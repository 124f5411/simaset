<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtdSetting extends Model
{
    use HasFactory;
    protected $table = 'setting_ttd';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
