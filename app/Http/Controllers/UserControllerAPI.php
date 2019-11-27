<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\User;

class UserControllerAPI extends Controller
{
    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function editprofile(Request $request)
    {
    	$request->validate([
            'name' => 'string|max:255',
            //'password' => 'required|string|min:3',
            // 'password_confirmation' => 'required|confirmed',
            'nif' => 'nullable|digits:9'
        ]);

        return User::where('id', $request->id)->update([
        	'name' => $request->name,
            //'password' => Hash::make($request->password),
            'nif' => $request->nif
            //'photo' => $request->photo
        ]);
	}
}
