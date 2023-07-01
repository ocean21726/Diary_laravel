<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $table = 'diary';
    public $timestamps = false;
    protected $primaryKey = 'idx';
}
