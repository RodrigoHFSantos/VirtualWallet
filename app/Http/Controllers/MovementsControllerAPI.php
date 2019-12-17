<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovementsControllerAPI extends Controller
{

    public function userMovements(){

        $user = Auth::user(); 

        $wallet = Wallet::where('email', $user->email)->first();

        $query = Movement::where('wallet_id', $wallet->id)
        ->join('categories', 'movements.category_id', '=', 'categories.id')
        ->leftJoin('wallets', 'movements.transfer_wallet_id', '=', 'wallets.id')
        ->orderBy('movements.date', 'desc')
        ->select('movements.*', 'wallets.email AS transfer_wallet', 'categories.name AS category_name')->get();

        //$movements = $query->paginate(10);

        // dd($movements);
        return $query;
    }
    






    public function register(Request $request)
    {   
        $wallet = Wallet::where('email', $request->email)->first();

        if($wallet){
            $movement = Movement::create([
                'wallet_id' => $wallet->id,
                'type' => 'i',
                'transfer' => 0,
                'value' => $request->value,
                'type_payment' => $request->type_payment,
                'source_description' => $request->source_description,
                'iban' => $request->iban,
                'start_balance' => $wallet->balance,
                'end_balance' => $wallet->balance + $request->value,
            ]);

            $wallet->balance += $request->value;
            $wallet->save();

            return $movement;

        }else{
            return response()->json(['message' => 'Não existe uma wallet com este email!'], 404);
        }
        
    }
}
