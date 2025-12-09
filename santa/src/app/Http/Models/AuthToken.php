<?php

namespace Sda\Santa\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model 
{
    protected $table = 'auth_tokens';
    protected $fillable = ['user_id', 'token', 'expires_at'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
