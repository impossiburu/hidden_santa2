<?php

namespace Sda\Santa\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Sda\Santa\Http\Models\User;

class SantaPair extends Model
{
    protected $table = 'santa_pairs';
    protected $fillable = ['giver_id', 'receiver_id'];
    public $timestamps = true;

    public function giver()
    {
        return $this->belongsTo(User::class, 'giver_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
