<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'transfer',
        'transfer_movement_id',
        'transfer_wallet_id',
        'type_payment',
        'category_id',
        'iban',
        'mb_entity_code',
        'mb_payment_reference',
        'description',
        'source_description',
        'date',
        'start_balance',
        'end_balance',
        'value',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = 'date';
    // public $timestamps = false;

    // protected $attributes = [
    //     'transfer' => 0,
    // ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    // falta relações entre a tabela movements e a wallets
    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }
}