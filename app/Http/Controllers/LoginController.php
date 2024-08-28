<?php

namespace App\Http\Controllers;

use App\Services\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }
    public function login(Request $request)
    {
        $request->validate([
            'debit_card_number' => 'required|string|size:16',
            'pin' => 'required|string|size:4',
        ]);

        $token = $this->loginService->login($request->debit_card_number, $request->pin);

        if ($token) {
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
