<?php

namespace Sda\Santa\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserRestriction extends Model
{
    protected $table = 'user_restrictions';
    protected $fillable = ['user_id', 'restricted_user_id'];
    public $timestamps = false;
}
