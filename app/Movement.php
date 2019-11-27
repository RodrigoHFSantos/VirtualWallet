<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'name',
        'email',
        'value',
        'type_payment',
        'iban',
        'description',
        'type',
        'category_id',
        'mb_entity_code',
        'mb_payment_reference',
        'source_description',
    ];

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
