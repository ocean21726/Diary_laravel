<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'diary_user';
    public $timestamps = false;
    protected $primaryKey = 'user_idx';
}
