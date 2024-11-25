<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all() , [
            'phone' => ['required','max:10','min:10'],
            'password' => ['required']
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "login failed",
                'data' =>$validator->errors()
            ]);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ] ,200);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
                'messages'=>'logged out successfully'
        ],200);
    }
}
