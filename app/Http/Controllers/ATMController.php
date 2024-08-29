<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ATMController extends BaseController
{
    public function deposit(DepositRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = Auth::user();
            $user->balance += $request->amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $request->amount,
            ]);
        });

        return $this->sendResponse(['new_balance' => Auth::user()->balance], 'Deposit successful');
    }

    public function withdraw(WithdrawRequest $request)
    {

        $user = Auth::user();

        if ($user->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient funds'], 400);
        }

        DB::transaction(function () use ($request, $user) {
            $user->balance -= $request->amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $request->amount,
            ]);
        });

        return $this->sendResponse(['new_balance' => Auth::user()->balance], 'Withdrawal successful');
    }

    public function balance()
    {
        return $this->sendResponse(['balance' => Auth::user()->balance], 'Balance retrieved successfully');
    }

    public function transactions()
    {
        $transactions = Auth::user()->transactions()->orderBy('created_at', 'desc')->get();
        return $this->sendResponse($transactions, 'Transactions retrieved successfully');
    }

}
