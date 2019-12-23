<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Routes for authentication
Route::post('register', 'RegisterControllerAPI@register')->name('register');
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');

//Routes for WalletControllerAPI
Route::middleware('auth:api')->get('wallets', 'WalletControllerAPI@index');
Route::get('wallets/total', 'WalletControllerAPI@totalWallets');
Route::post('wallets/create', 'WalletControllerAPI@create');


//Routes for Users
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');
Route::middleware('auth:api')->put('users/me', 'UserControllerAPI@editprofile')->name('editprofile');

//Routes for Movements
Route::middleware('auth:api')->post('movements/register/income', 'MovementsControllerAPI@registerIncome');
Route::middleware('auth:api')->get('/wallet/movements/me','MovementsControllerAPI@userMovements');
Route::middleware('auth:api')->get('movements/ids', 'MovementsControllerAPI@myIdsMovements');
Route::middleware('auth:api')->get('movements/users/filter', 'MovementsControllerAPI@filterUserMovements');
Route::middleware('auth:api')->get('movements/users/emails', 'MovementsControllerAPI@emailsFromMyMovements');
Route::middleware('auth:api')->post('movements/users/register/expense', 'MovementsControllerAPI@registerExpense');
Route::middleware('auth:api')->get('movements/details/photo', 'MovementsControllerAPI@photoMovementTransfer');

//routes for wallet
Route::middleware('auth:api')->get('wallet/me','WalletControllerAPI@currentBalance');

//Routes for Categories
Route::middleware('auth:api')->get('categories/names', 'CategoryControllerAPI@categoriesNames');