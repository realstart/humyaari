<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function login(Request $request) {

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json(['message' => 'User not found'], 401);

        if (!Auth::attempt($request->only('email', 'password')))
            return response()->json(['message' => 'User name or password mismatch'], 401);


        try {
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'loginInvalid'], 401);
            }
        } 
        catch (JWTException $e) 
        {
            return response()->json(['error' => 'loginTokenError'], 500);
        }

        date_default_timezone_set("Asia/Dhaka");
        $login_time = date('Y-m-d H:i:s');
        
        
        DB::statement("UPDATE users SET updated_at = '$login_time' WHERE email='$request->email'");
        return response()->json([
            'user'  => $user,
            'token' => $token
        ], 200);
    }

    public function userLog(){
        // $user_log = DB::select(DB::raw("SELECT `name`,`email`,`updated_at` FROM users order by updated_at Desc"));
        // return response()->json([
        //     'userLog' => $user_log
        // ]);

        return new UserCollection(User::select('users.id','users.name','users.email','users.updated_at')->orderBy('users.updated_at','DESC')->paginate(10));
    }
}