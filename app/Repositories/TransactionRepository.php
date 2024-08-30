<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    protected $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function all()
    {
        return $this->model->with('user')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }
}
