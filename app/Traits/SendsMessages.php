<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait SendsMessages
{
    public function sendMessage($phone , $action)
    {
        do {
            $code = random_int(1000, 9999);
        } while (Cache::has($code));

        Cache::forever($phone, $code);
        Cache::forever('action'.$code, $action);

//        return [
//            'phone' => $phone,
//            'code' => $code,
//        ];
    }
}
