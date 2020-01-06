<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Wallet;
use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Resource;

class MovementsControllerAPI extends Controller
{

    public function numberOfCategoriesUsed(){
        
    }

    public function movementsPerMonth(){
        for($i = 1; $i < 13; $i++) {
            $movements[$i] = Movement::whereMonth('date', $i)->get('id');
        }
       //$movements = DB::table('movements')->select('date', 'count(date) as num')->gro\upBy('date')->count();
       //$movements = DB::table('movements')->select('date')->groupBy('date')->count();
       return $movements;
   }
    
    public function numberOfUsersPerTypes(){
        $typeUsers[0] = User::where('type', 'u')->count();
        $typeUsers[1] = User::where('type', 'o')->count();
        $typeUsers[2] = User::where('type', 'a')->count();
        return $typeUsers;
    }

    public function editMovement(Request $request){
        $category = Category::where('name', $request->category)->first();
        if(Movement::where('id', $request->id)->update(['category_id' => $category->id ,'description' => $request->description]) == 1){
            $movement_edited = Movement::where('id', $request->id)->first();
            return $movement_edited;
        }else{
            return response()->json(['message' => 'Error editing movement'], 404);
        }
    }

    public function registerExpense(Request $request)
    {   
        $user = Auth::user(); 
        $wallet = Wallet::where('email', $user->email)->first();

        $transfer = 0;
        $wallet_transfer_id = $request->email_destination; //para a variavel ficar a null visto que nao é transfer
        if($request->input('type_movement_selected') === 'Transfer'){
            $wallet_transfer = Wallet::where('email', $request->email_destination)->first();
            if($wallet_transfer != null){
                $transfer = 1;
                $wallet_transfer_id =  $wallet_transfer->id;
            }else{
                return response()->json(['message' => 'Não existe uma wallet com este email!'], 404);
            }
        }

        if($request->input('value') > 5000 || $request->input('value') < 0.01){
            return response()->json(['message' => 'Valor tem que ser entre 0.01€ e 5000€!'], 404);
        }

        $payment_type = $request->input('movement_payment_type_selected');
        if($request->has('movement_payment_type_selected') && $request->input('movement_payment_type_selected') !== 'null'){
            if($request->input('movement_payment_type_selected') === 'Bank Transfer'){
                $payment_type = 'bt';
                $request->merge([
                    'movement_payment_type_selected' => $payment_type,
                ]);
            }
            if($request->input('movement_payment_type_selected') === 'Multibanco/MB payment'){
                $payment_type = 'mb';
                $request->merge([
                    'movement_payment_type_selected' => $payment_type,
                ]);
            }
        }
        
        $category = Category::where('name', $request->category_selected)->first();

        if($wallet){
            $movement = Movement::create([
                'wallet_id' => $wallet->id,
                'type' => 'e',
                'transfer' => $transfer,
                'value' => $request->value,
                'category_id' => $category->id,
                'description' => $request->description,
                'type_payment' => $request->movement_payment_type_selected,
                'iban' => $request->iban,
                'mb_entity_code' => $request->mb_code,
                'mb_payment_reference' => $request->mb_reference,
                'transfer_wallet_id' => $wallet_transfer_id,
                'source_description' => $request->source_description,
                'start_balance' => $wallet->balance,
                'end_balance' => $wallet->balance - $request->value,
            ]);
            $wallet_balance = $wallet->balance - $request->value;
            Wallet::where('email', $wallet->email)->update(['balance' => $wallet_balance]);
            if($transfer === 1){
                $this->registerIncome($request);
            }
        }else{
            return response()->json(['message' => 'Não existe uma wallet com este email!'], 404);
        }
    }

    public function registerIncome(Request $request)
    {   
        
        if($request->has('email_destination')){
            $wallet = Wallet::where('email', $request->email_destination)->first();
            $transfer = 1;
            $wallet_transfer = Wallet::where('email', $request->senderEmail)->first();
            $wallet_transfer_id = $wallet_transfer->id;
        }else{
            $wallet = Wallet::where('email', $request->email)->first();
            $transfer = 0;
            $wallet_transfer_id = Wallet::where('email', $request->senderEmail)->first();
        }

        if($request->input('value') > 5000 || $request->input('value') < 0.01){
            return response()->json(['message' => 'Valor tem que ser entre 0.01€ e 5000€!'], 404);
        }
        
        if($wallet){
            $movement = Movement::create([
                'wallet_id' => $wallet->id,
                'type' => 'i',
                'transfer' => $transfer,
                'value' => $request->value,
                'type_payment' => $request->movement_payment_type_selected,
                'iban' => $request->iban,   
                'mb_entity_code' => $request->mb_code,
                'mb_payment_reference' => $request->mb_reference,
                'transfer_wallet_id' => $wallet_transfer_id,
                'source_description' => $request->source_description,
                'start_balance' => $wallet->balance,
                'end_balance' => $wallet->balance + $request->value,
            ]);
            
            $wallet->balance += $request->value;
            Wallet::where('email', $wallet->email)->update(['balance' => $wallet->balance]);
            // $wallet->save();
            return $movement;
        }else{
            return response()->json(['message' => 'Não existe uma wallet com este email!'], 404);
        }
        
    }

    public function emailsFromMyMovements()
    {
        $movements = $this->userMovements()->where('transfer_wallet', '!=', null)->toArray();
        $emails = array_column($movements, 'transfer_wallet');
        return $emails;
    }

    public function myIdsMovements()
    {
        $user = Auth::user(); 
        $wallet = Wallet::where('email', $user->email)->first('id');
        $ids = Movement::where('wallet_id', $wallet->id)->get('id');
        return $ids;
    }

    public function userMovements()
    {
        $user = Auth::user(); 

        $wallet = Wallet::where('email', $user->email)->first();

        $movements = Movement::where('wallet_id', $wallet->id)
        ->leftJoin('categories', 'movements.category_id', '=', 'categories.id')
        ->leftJoin('wallets', 'movements.transfer_wallet_id', '=', 'wallets.id')
        ->orderBy('movements.date', 'desc')
        ->select('movements.*', 'wallets.email AS transfer_wallet', 'categories.name AS category_name')->get();

        return $movements;
    }
    
     public function filterUserMovements(Request $request)
    {
        $user = Auth::user(); 
        $wallet = Wallet::where('email', $user->email)->first();
        $movements_filtered =  $this->userMovements();
        // Search for a movement based on their id.
        if ($request->input('id') != null) {
            $movements_filtered = $movements_filtered->where('id', $request->input('id'));
        }
        // Search for a movement based on their category
        if ($request->input('category') != null) {
            $category = Category::where('name', $request->input('category'))->first();
            $movements_filtered = $movements_filtered->where('category_id', $category->id);
        }
        // Search for a movement based on their type (income or expense).
        if ($request->has('movement_type') && ($request->input('movement_type') != null)) {
            if($request->input('movement_type') == 'Expense'){
                $movement_type = 'e';
            }
            if($request->input('movement_type') == 'Income'){
                $movement_type = 'i';
            }
            $movements_filtered = $movements_filtered->where('type', $movement_type);
        }
        // Search for a movement based on their movement_payment.
        if ($request->has('movement_payment') && ($request->input('movement_payment') != null)) {
            if($request->input('movement_payment') == 'Cash'){
                $movement_payment = 'c';
            }
            if($request->input('movement_payment') == 'Bank Tranfer'){
                $movement_payment = 'bt';
            }
            if($request->input('movement_payment') == 'Multibanco/MB payment'){
                $movement_payment = 'mb';
            }
            $movements_filtered = $movements_filtered->where('type_payment', $movement_payment);
        }
        // Search for a movement based on their transfer email.
        if ($request->has('transfer_email') && ($request->input('transfer_email') != null)) {
            $transfer_wallet = Wallet::where('email', $request->input('transfer_email'))->first();
            $movements_filtered = $movements_filtered->where('transfer_wallet_id',  $transfer_wallet->id);
        }
        // Search for a movement based on the range date.
        if ($request->has('range_date') && ($request->input('range_date') != null)) {
            $start_date = date('Y-m-d h:i:s', strtotime($request->input('start_date')));
            $end_date = date('Y-m-d h:i:s', strtotime($request->input('end_date')));
            $movements_filtered = $movements_filtered->where('date', '>=', $start_date);
            $movements_filtered = $movements_filtered->where('date', '<=', $end_date);
        }
        return $movements_filtered;
    }

    public function photoMovementTransfer(Request $request)
    {
        //Buscar a wallet do transfer_wallet_id do movement
        $movement = Movement::where('id', $request->input('id'))->first();

        $wallet = Wallet::where('id', $movement->transfer_wallet_id)->first();

        if($wallet != null){
            $user = User::where('email',$wallet->email)->first();
        }else{
            return $photo = null;
        }

        $photo = $user->photo;

        
        return $photo;
    }

}
