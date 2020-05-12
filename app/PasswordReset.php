<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_reset';
    protected $fillable = ['uid', 'hash'];
    public $timestamps = false;
}
