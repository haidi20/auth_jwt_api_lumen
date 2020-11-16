<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $token = app('auth')->attempt($request->only('name', 'password'));
        // $insert = $request->only('name', 'password');

        $login = User::where(["name" => $request->name])->first();

        $user = "";

        if(app()->make('hash')->check($request->password, $login->password)) {
            $user = User::where(["name" => $request->name]);
            $user->update(['token' => $token]);
        }

        if($token != null) {
            $token = "bearer ".$token;
        }else {
            $token = null;
        }
    
        return $this->respondWithToken($token);
    }
}