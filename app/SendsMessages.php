<?php

namespace App;
use Illuminate\Support\Facades\Cache;

trait SendsMessages
{
    public function sendMessage($phone)
    {
        do {
            $code = random_int(1000, 9999);
        } while (Cache::has($code));

        Cache::forever($phone, $code);

        return [
            'phone' => $phone,
            'code' => $code,
        ];
    }
}
