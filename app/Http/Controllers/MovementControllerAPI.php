<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Wallet;
use Illuminate\Http\Request;

class MovementControllerAPI extends Controller
{
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
        
        // $request->validate([
        //     'email' => 'required|string|email|max:255',
        //     'value' => 'required|digits|min:1',
        //     'typeOfPayment' => 'required|digits:9',
        //     'iban' => 'string|max:25',
        //     'description' => 'required|string'
        // ]);
    }
}
