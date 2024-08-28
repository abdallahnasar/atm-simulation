<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function login(string $debitCardNumber, string $pin): ?string
    {
        $user = $this->userModel->where('debit_card_number', $debitCardNumber)->first();

        if ($user && Hash::check($pin, $user->pin)) {
            Auth::login($user);
            return $user->createToken('auth_token')->accessToken;
        }

        return null;
    }
}
