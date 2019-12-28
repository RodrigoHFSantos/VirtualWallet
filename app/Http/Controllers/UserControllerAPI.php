<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use DB;

class UserControllerAPI extends Controller
{
    public function removeUser(Request $request){
        if(User::where('email', $request->input('email'))->delete()){
            return response()->json(['message' => 'User deleted!'], 200);
        }
        return response()->json(['message' => 'Error deleting user!'], 404);
    }

    public function activateOrDesactivate(Request $request)
    {        
        if($request->input('active_value') == 0){
            User::where('id', $request->input('id'))->update(['active' => '1']);
            return response()->json(['message' => 'User activated!'], 200);
        }
        if($request->input('active_value') == 1){
            User::where('id', $request->input('id'))->update(['active' => '0']);
            return response()->json(['message' => 'User deactivated!'], 200);
        }
        return response()->json(['message' => 'Error'], 404);
    }

    public function filter(Request $request)
    {
        $users = $this->allUsers();
        // Search for a movement based on their type.
        if ($request->input('type_selected') != null) {
            if($request->input('type_selected') === 'Operator'){
                $type = 'o';
            }
            if($request->input('type_selected') === 'User'){
                $type = "u";
            }
            if($request->input('type_selected') === 'Administrator'){
                $type = 'a';
            }
            $users = $users->where('type', $type);
            // dd($users);
        }
        // Search for a movement based on their name.
        if ($request->input('name') != null) {
            $users = $users->where('name', $request->input('name'));
        }
        // Search for a movement based on their email.
        if ($request->input('email') != null) {
            $users = $users->where('email', $request->input('email'));
        }
        // Search for a movement based on their status.
        if ($request->input('status') != null) {
            if($request->input('status') == 'Active'){
                $status = 1;
                $users = $users->where('active', $status);
            }
            if($request->input('status') == 'Inactive'){
                $status = 0;
                $users = $users->where('active', $status);
            }
        }
        return $users;
    }

    public function userNamesEmails(){
        return DB::table('users')->select(array('name', 'email'))->get();
    }

    public function allUsers()
    {
        $type_users = DB::table('users')->join('wallets', 'users.id', '=', 'wallets.id')
        ->select('users.*', 'wallets.balance')
        ->orderBy('users.created_at', 'desc')
        ->get();

        $type_admin_operator = User::where('type', '!=', 'u')->orderBy('users.created_at', 'desc')->get();
        foreach ($type_admin_operator as $user) {
            $user = $user->balance = "0.00";
        }    

        return $type_admin_operator->toBase()->merge($type_users->toBase());
    }

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
