<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $table = 'email_verification';
    protected $fillable = ['uid', 'hash'];
    public $timestamps = false;
}
