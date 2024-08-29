<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ATMService
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function deposit(User $user, $amount): array
    {
        DB::transaction(function () use ($user, $amount) {
            $user->balance += $amount;
            $user->save();

            $this->transaction->create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $amount,
            ]);
        });

        return ['message' => 'successful Deposit Transaction', 'balance' => $user->balance];

    }

    public function withdraw(User $user, $amount): array
    {
        DB::transaction(function () use ($user, $amount) {
            $user->balance -= $amount;
            $user->save();

            $this->transaction->create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $amount,
            ]);
        });

        return ['message' => 'successful Withdrawal Transaction', 'balance' => $user->balance];
    }

    public function getBalance(User $user)
    {
        return $user->balance;
    }

    public function getTransactions(User $user)
    {
        return $user->transactions()->orderBy('created_at', 'desc')->get();
    }
}
