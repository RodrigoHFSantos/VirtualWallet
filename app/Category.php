<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // use Enums;
    
    protected $table = 'categories';

    // //não sei se está correto
    // protected $enumTypes = [
    //     'e', //expense category
    //     'i', //income category
    // ];

    protected $fillable = [
        'name',
        'type',
    ];

    public function movements()
    {
        return $this->hasMany('App\Movement');
    }
}
