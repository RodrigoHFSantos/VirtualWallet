<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterControllerAPI extends Controller
{
    public function register(Request $request)
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
        }else{
            $filename = null;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3',
            // 'password_confirmation' => 'required|confirmed',
            'nif' => 'nullable|digits:9|unique:users'
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nif' => $request->nif,
            'photo' => $filename,
            'type' => $request->role,
        ]);

    }
}
