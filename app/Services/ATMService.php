<?php

    namespace App\Services;

    use App\Models\User;
    use App\Models\Transaction;
    use App\Repositories\TransactionRepository;
    use Illuminate\Support\Facades\DB;

    class ATMService
    {
        protected $transactionRepository;

        public function __construct(TransactionRepository $transactionRepository)
        {
            $this->transactionRepository = $transactionRepository;
        }

        public function deposit(User $user, $amount): array
        {
            DB::transaction(function () use ($user, $amount) {
                $user->balance += $amount;
                $user->save();

                $this->transactionRepository->createTransaction($user->id, 'deposit', $amount);
            });

            return ['message' => 'successful Deposit Transaction', 'balance' => $user->balance];

        }

        public function withdraw(User $user, $amount): array
        {
            DB::transaction(function () use ($user, $amount) {
                $user->balance -= $amount;
                $user->save();

                $this->transactionRepository->createTransaction($user->id, 'withdrawal', $amount);
            });

            return ['message' => 'successful Withdrawal Transaction', 'balance' => $user->balance];
        }

        public function getBalance(User $user)
        {
            return $user->balance;
        }

        public function getTransactions(User $user)
        {
            return $this->transactionRepository->getTransactionsOfUser($user);
        }
    }
