<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet;
use App\Http\Resources\WalletResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletControllerAPI extends Controller
{
    public function create(Request $request)
    {
        return Wallet::create([
            'id' => User::where('email', $request->email)->value('id'),
            'email' => $request->email,
        ]);
    }

    public function index()
    {
        return WalletResource::collection(Wallet::all()); 
    }

    public function totalWallets()
    {
        return Wallet::count();
    }

    public function currentBalance()
    {
        $user = Auth::user(); 
        $wallet = Wallet::where('email', $user->email)->first();
        $currentBalance = $wallet->balance;
        return $currentBalance;
    }
}
