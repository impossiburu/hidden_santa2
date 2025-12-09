<?php

namespace Sda\Santa\Http\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    public $timestamps = true;

    public function restrictedUsers()
    {
        return $this->hasMany(UserRestriction::class, 'user_id');
    }

    public function santaReceiver()
    {
        return $this->hasOne(SantaPair::class, 'giver_id');
    }

}
