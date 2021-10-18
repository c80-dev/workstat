<?php

namespace App\Helpers;

class Unique
{
    public function uniqueNumber($length)
    {
        $today = sprintf("%0.9s",str_shuffle(rand(12,30000) * time()));
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $main = $today."". $characters;
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($main) - 1);
           return $randomString .= $main[$index];
        }
    }

}
