<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;

class TransactionRepository
{
    protected $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function allPaginated($search = [])
    {
        $query = $this->model->newQuery();
        if (count($search)) {
            foreach($search as $key => $value) {
                if (in_array($key, ['user_id', 'amount', 'type', 'status']) && $value) {
                    $query->where($key, $value);
                }
            }
        }

        return $query->with('user')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getTransactionsOfUser(User $user)
    {
        return $user->transactions()->orderBy('created_at', 'desc')->paginate(10);
    }

    public function createTransaction($userId, $type, $amount)
    {
        return $this->model->create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
        ]);
    }


}
