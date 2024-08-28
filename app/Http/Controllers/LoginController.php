<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;

class LoginController extends BaseController
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(LoginRequest $request)
    {
        $token = $this->loginService->login($request->debit_card_number, $request->pin);

        if ($token) {
            return $this->sendResponse(['token' => $token], 'User logged in successfully');
        }

        return $this->sendError('Invalid credentials', 401);
    }
}
