<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;

class LoginService
{
    protected $userModel;
    protected $hasher;

    public function __construct(User $userModel, Hasher $hasher)
    {
        $this->userModel = $userModel;
        $this->hasher = $hasher;
    }

    public function login(string $debitCardNumber, string $pin): ?string
    {
        $user = $this->userModel->where('debit_card_number', $debitCardNumber)->first();

        if (!$user || !$this->hasher->check($pin, $user->pin)) {
            return null;
        }

        return $user->createToken('Personal Access Token')->accessToken;
    }
}
