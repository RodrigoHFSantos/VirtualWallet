<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginControllerAPI extends Controller
{
     
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('YOUR_SERVER_URL').'/oauth/token', [
                'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
        ]);

        $errorCode= $response->getStatusCode();

        if ($errorCode=='200') {
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Logged out successfully!'], 200);
    }
}
