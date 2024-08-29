<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawRequest;
use App\Services\ATMService;
use Illuminate\Support\Facades\Auth;

class ATMController extends BaseController
{

    protected $atmService;

    public function __construct(ATMService $atmService)
    {
        $this->atmService = $atmService;
    }

    public function deposit(DepositRequest $request)
    {
        $result = $this->atmService->deposit(Auth::user(), $request->amount);
        return $this->sendResponse(['new_balance' => $result['balance']], $result['message']);
    }

    public function withdraw(WithdrawRequest $request)
    {
        $result = $this->atmService->withdraw(Auth::user(), $request->amount);
        return $this->sendResponse(['new_balance' => $result['balance']], $result['message']);
    }

    public function balance()
    {
        return $this->sendResponse(['balance' => $this->atmService->getBalance(Auth::user())],
            'Balance retrieved successfully');
    }

    public function transactions()
    {
        $transactions = $this->atmService->getTransactions(Auth::user());
        return $this->sendResponse($transactions, 'Transactions retrieved successfully');
    }

}
