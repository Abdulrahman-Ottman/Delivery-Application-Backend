<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{
    public function index($phone)
    {
        $messages['code'] = Cache::get($phone);
        if($messages['code'])
            $messages['phone'] = $phone;
        return view('messages', compact('messages'));
    }
    public static function sendMessage($phone)
    {
        $code=random_int(100000, 999999);
        Cache::put($phone, $code, 300);
        return response()->json (['phone' => $phone,'code' => $code]);
    }
}

