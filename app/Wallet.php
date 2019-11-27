<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['id', 'email'];

    protected $attributes = [
        'balance' => 0,
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }

    public function movements()
    {
        return $this->hasMany('App\Movement', ['wallet_id', 'transfer_wallet_id']);
    }
}
