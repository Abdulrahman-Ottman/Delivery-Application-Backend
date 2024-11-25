<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => ['required','max:10','min:10'],
            'password' => ['required','min:8'],
            'code' => ['required','min:6','max:6'],
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Register failed",
                'data' =>$validator->errors()
            ]);
        }
        $phone = $request->input('phone');
        $password = $request->input('password');
        $code = $request->input('code');
        $sms = Message::where('phone',$phone)->latest()->first();
        if(!$sms)
            return response()->json (['message' => 'Incorrect code'], 400);
        if($sms['code']!=$code)
            return response()->json (['message' => 'Incorrect code'], 400);
        User::create([
            'phone'=>$phone,
            'password'=>Hash::make($password),
            'phone_verified_at'=>Carbon::now()
        ]);
        return response(['message' => 'User created successfully']);
    }
    public function checkPhoneAndPassword(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => ['required','max:10','min:10'],
            'password' => ['required','min:8'],
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Invalid input",
                'data' =>$validator->errors()
            ]);
        }
        $phone = $request->input('phone');
        if(User::query()->where('phone', $phone)->first())
            return response()->json (['message' => 'The number is already in use'], 409);
        if($phone[0]!=0||$phone[1]!=9)
            return response()->json (['message' => 'Number should start with 09'], 400);
        return response()->json (['message' => 'Done']);
    }
}
