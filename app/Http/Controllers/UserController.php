<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function completeUserInfo(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'phone' => ['required','max:10','min:10'],
            'first_name' => 'required',
            'last_name' => 'required',
            'location' => 'required',
            'image' => 'nullable',
        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => "Invalid input",
                'data' =>$validator->errors()
            ]);
        }
        $phone = $request->input('phone');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $location = $request->input('location');
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/profile-images', 'public');
        }
        $user = User::where('phone', $phone);
        if(!$user->first())
            return response()->json(['message' => 'User not found'], 404);
        $user->update(
            [
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'location'=>$location,
                'image' => $imagePath,
            ]
        );
        return response(['message' => 'User info updated successfully']);
    }

    public function getUserImage($phone)
    {
        $user = User::where('phone', $phone)->first();
        if ($user) {
            if ($user->image) {
                $imagePath = storage_path('app/public/' . $user->image);
                if (file_exists($imagePath)) {
                    return response()->file($imagePath);
                }
            }
            return response()->json([
                'message' => 'Image file not found'
            ], 404);
        }

        return response()->json([
            'message' => 'User not found'
        ], 404);
    }
    public function getUserInfo($phone)
    {
        $user = User::where('phone', $phone)->first();
        if ($user) {
            $userInfo = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'location' => $user->location,
            ];
                return response()->json([
                    'data' => $userInfo,
                ]);
        }
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }
}
