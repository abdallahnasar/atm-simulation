<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $transactionRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionRepo = new TransactionRepository(new Transaction());
    }

    public function testAllPaginated()
    {
        $user = User::factory()->create();
        Transaction::factory()->count(15)->create(['user_id' => $user->id]);

        $search = ['user_id' => $user->id];
        $transactions = $this->transactionRepo->allPaginated($search);

        $this->assertCount(10, $transactions);
        $this->assertEquals($user->id, $transactions->first()->user_id);
    }

    public function testFind()
    {
        $transaction = Transaction::factory()->create();
        $foundTransaction = $this->transactionRepo->find($transaction->id);

        $this->assertNotNull($foundTransaction);
        $this->assertEquals($transaction->id, $foundTransaction->id);
    }

    public function testGetTransactionsOfUser()
    {
        $user = User::factory()->create();
        Transaction::factory()->count(5)->create(['user_id' => $user->id]);

        $transactions = $this->transactionRepo->getTransactionsOfUser($user);

        $this->assertCount(5, $transactions);
        $this->assertEquals($user->id, $transactions->first()->user_id);
    }

    public function testCreateTransaction()
    {
        $user = User::factory()->create();
        $transactionData = [
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 100,
        ];

        $transaction = $this->transactionRepo->createTransaction(
            $transactionData['user_id'],
            $transactionData['type'],
            $transactionData['amount']
        );

        $this->assertDatabaseHas('transactions', $transactionData);
        $this->assertEquals($transactionData['user_id'], $transaction->user_id);
        $this->assertEquals($transactionData['type'], $transaction->type);
        $this->assertEquals($transactionData['amount'], $transaction->amount);
    }
}
