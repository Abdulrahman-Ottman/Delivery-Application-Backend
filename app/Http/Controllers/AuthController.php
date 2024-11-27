<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\SendsMessages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use SendsMessages;
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
    public function verify(Request $request){
        $validator = Validator::make($request->all() , [
            'phone' => ['required' ,'unique:users,phone' , 'regex:/^+(d{1,3})[-.s]?(?(d{1,4}))?[-.s]?(?(d{1,4}))?[-.s]?d{3,10}$/'],
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Verifying failed",
                'data' =>$validator->errors()
            ]);
        }
        $phone = $request->input('phone');

        $response = $this->sendMessage($phone);
        return response()->json($response);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => ['required','max:10','min:10'],
            'code' => ['required','max:4','min:4'],
            'password' => ['required','min:8'],
            'first_name' => 'required',
            'last_name' => 'required',
            'location' => 'required',
            'image ' =>'image|mimes:png,jpg'
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Registration failed",
                'data' =>$validator->errors()
            ]);
        }
        $phone = $request->input('phone');
        $password = $request->input('password');
        $code = $request->input('code');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $location = $request->input('location');
        if(Cache::get($phone)!=$code)
            return response()->json (['message' => 'Incorrect code'], 400);

        $path = $request->file('image')->store('profile-images', 'public');

        $user=User::create([
            'phone'=>$phone,
            'password'=>Hash::make($password),
            'phone_verified_at'=>Carbon::now(),
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'location'=>$location,
            'image' => $path
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Cache::forget($phone);

        return response()->json([
            'access_token' => $token,
        ] ,200);
    }
}
