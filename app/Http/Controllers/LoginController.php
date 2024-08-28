<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'debit_card_number' => 'required|string|size:16',
            'pin' => 'required|string|size:4',
        ]);

        $user = User::where('debit_card_number', $request->debit_card_number)->first();

        if ($user && Hash::check($request->pin, $user->pin)) {
            Auth::login($user);
            $token = $user->createToken('auth_token')->accessToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
