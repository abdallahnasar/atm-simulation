<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionRepository;

    protected $userRepository;

    public function __construct(TransactionRepository $transactionRepository, UserRepository $userRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $transactions = $this->transactionRepository->allPaginated($request->all());
        $users = $this->userRepository->all(['id', 'name']);
        return view('admin.transactions.index', compact('transactions', 'users'));
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }
}
