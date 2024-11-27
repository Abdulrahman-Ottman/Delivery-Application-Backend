<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{

    public function show($phone)
    {
        $messages['code'] = Cache::get($phone);
        if($messages['code'])
            $messages['phone'] = $phone;
        return view('messages', ['messages' => $messages]);
    }
}

