<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;

class UserController extends Controller
{
//    public function getUserImage($phone)
//    {
//        $user = User::where('phone', $phone)->first();
//        if ($user) {
//            if ($user->image) {
//                $imagePath = storage_path('app/public/' . $user->image);
//                if (file_exists($imagePath)) {
//                    return response()->file($imagePath);
//                }
//            }
//            return response()->json([
//                'message' => 'Image file not found'
//            ], 404);
//        }
//
//        return response()->json([
//            'message' => 'User not found'
//        ], 404);
//    }
//    public function getUserInfo($phone)
//    {
//        $user = User::where('phone', $phone)->first();
//        if ($user) {
//            $userInfo = [
//                'first_name' => $user->first_name,
//                'last_name' => $user->last_name,
//                'location' => $user->location,
//            ];
//            return response()->json([
//                'data' => $userInfo,
//            ]);
//        }
//        return response()->json([
//            'message' => 'User not found'
//        ], 404);
//    }
}
