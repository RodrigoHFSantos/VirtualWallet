<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserControllerAPI extends Controller
{
    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }
}
