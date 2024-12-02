<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\SendsMessages;
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
            'phone' => ['required' ,'regex:/^\+(\d{1,3})[-.\s]?\(?(\d{1,4})\)?[-.\s]?\(?(\d{1,4})\)?[-.\s]?\d{4,10}$/'],
            'password' => ['required' , 'min:8']
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "login failed",
                'data' =>$validator->errors()
            ],401 );
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
    public function sendCode(Request $request){
        $validator = Validator::make($request->all() , [
            'phone' => ['required',
                'unique:users,phone',
                'regex:/^\+(\d{1,3})[-.\s]?\(?(\d{1,4})\)?[-.\s]?\(?(\d{1,4})\)?[-.\s]?\d{4,10}$/'
            ],
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Sending failed",
                'data' =>$validator->errors()
            ],401);
        }
        $phone = $request->input('phone');
        $this->sendMessage($phone);
        return response()->json(['message' => 'Code sent successfully'],200);
    }
    public function verify(Request $request){
        $validator = Validator::make($request->all() , [
            'phone' => ['required' ,
                'unique:users,phone',
                'regex:/^\+(\d{1,3})[-.\s]?\(?(\d{1,4})\)?[-.\s]?\(?(\d{1,4})\)?[-.\s]?\d{4,10}$/'
            ],
            'code'  => ['required','min:4','max:4']
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Verifying failed",
                'data' =>$validator->errors()
            ],401);
        }
        $phone = $request->input('phone');
        $code = $request->input('code');
        if(Cache::get($phone)!=$code)
            return response()->json (['message' => 'Incorrect code'], 400);
        $token = base64_encode($phone);

        Cache::forever($token, $phone);
        Cache::forget($phone);

        return response()->json([
            'message' => 'Verification successful',
            'token' => $token
        ],200);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'token' => 'required',
            'password' => ['required','min:8', 'confirmed'],
            'first_name' => 'required',
            'last_name' => 'required',
            'location.country' => ['required' , 'string'],
            'location.city' =>  ['required' , 'string'],
            'image ' =>'image|mimes:png,jpg'
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Registration failed",
                'data' =>$validator->errors()
            ],401);
        }
        $token = $request->input('token');
        if(!Cache::get($token))
            return response()->json (['message' => 'Incorrect token'], 400);
        $phone = base64_decode($token);
        $password = $request->input('password');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $location = $request->input('location');
        $path=null;
        if($request->file('image'))
            $path = $request->file('image')->store('images/profile-images', 'public');

        $user=User::create([
            'phone'=>$phone,
            'password'=>Hash::make($password),
            'phone_verified_at'=>Carbon::now(),
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'location'=>$location,
            'image' => $path
        ]);
        Cache::forget($token);

        $accessToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
        ] ,200);
    }
}
