<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserControllerAPI extends Controller
{
    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function editprofile(Request $request)
    {
        if (!is_null($request->photo)) {
           $exploded = explode(',', $request->photo);
            $decoded = base64_decode($exploded[1]);

            if (strpos($exploded[0], 'jpeg')) {
                $extension = 'jpg';
            }else{
                $extension = 'png';
            } 
            
            $str=rand(0, 10000); 
            
            $filename = $str.'.'.$extension;

            $path = 'storage/fotos/'.$filename;

            file_put_contents($path, $decoded);

            User::where('id', $request->id)->update([
                'photo' => $filename
            ]);
        }
    	

    	$request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:3',
            'nif' => 'nullable|digits:9'
        ]);

        User::where('id', $request->id)->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'nif' => $request->nif
        ]);

        return User::where('id', $request->id)->first();

	}
}
