<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet;
use App\Http\Resources\Wallet as WalletResource;

class WalletControllerAPI extends Controller
{
    public function index()
    {
        return WalletResource::collection(Wallet::all()); 
    }

    public function totalWallets(){
        return Wallet::count();
    }
}
