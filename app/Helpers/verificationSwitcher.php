<?php

namespace App\Helpers;

class verificationSwitcher
{
    public static function verification_switch(String $way)
    {
        return config('verification.way') == $way;
    }
}
