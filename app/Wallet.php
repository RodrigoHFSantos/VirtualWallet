<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['email'];

    protected $attributes = [
        'balance' => 0,
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'email');
    }
}
