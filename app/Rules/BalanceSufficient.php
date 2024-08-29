<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BalanceSufficient implements Rule
{

    public function passes($attribute, $value)
    {
        $user = Auth::user();
        return $user->balance >= $value;
    }

    public function message()
    {
        return 'Insufficient funds';
    }
}
