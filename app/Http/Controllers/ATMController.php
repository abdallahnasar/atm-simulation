<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawRequest;
use App\Http\Resources\TransactionResource;
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
        try {
            $result = $this->atmService->deposit(Auth::user(), $request->amount);
            return $this->sendResponse(['new_balance' => $result['balance']], $result['message']);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function withdraw(WithdrawRequest $request)
    {
        try {
            $result = $this->atmService->withdraw(Auth::user(), $request->amount);
            return $this->sendResponse(['new_balance' => $result['balance']], $result['message']);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function balance()
    {
        try {
            return $this->sendResponse(['balance' => $this->atmService->getBalance(Auth::user())],
                'Balance retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function transactions()
    {
        try {
            $transactions = $this->atmService->getTransactions(Auth::user());
            return $this->sendResponse(
                TransactionResource::collection($transactions),
                'Transactions retrieved successfully',
                200,
                true
            );
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

}
