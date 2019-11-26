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
Route::get('wallets', 'WalletControllerAPI@index');
Route::get('wallets/total', 'WalletControllerAPI@totalWallets'); //numero de wallets existentes 
Route::post('wallets/create', 'WalletControllerAPI@create');


//Routes for Users
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');