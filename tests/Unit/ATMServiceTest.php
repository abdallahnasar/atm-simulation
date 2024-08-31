<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\ATMService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ATMServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $atmService;
    protected $transactionRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionRepo = $this->createMock(TransactionRepository::class);
        $this->atmService = new ATMService($this->transactionRepo);
    }

    public function testDeposit()
    {
        $user = User::factory()->create(['balance' => 100]);
        $amount = 50;

        $this->transactionRepo->expects($this->once())
            ->method('createTransaction')
            ->with($user->id, 'deposit', $amount);

        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) use ($user, $amount) {
            $callback();
        });

        $result = $this->atmService->deposit($user, $amount);

        $this->assertEquals(150, $user->fresh()->balance);
        $this->assertEquals('successful Deposit Transaction', $result['message']);
        $this->assertEquals(150, $result['balance']);
    }

    public function testWithdraw()
    {
        $user = User::factory()->create(['balance' => 100]);
        $amount = 50;

        $this->transactionRepo->expects($this->once())
            ->method('createTransaction')
            ->with($user->id, 'withdrawal', $amount);

        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) use ($user, $amount) {
            $callback();
        });

        $result = $this->atmService->withdraw($user, $amount);

        $this->assertEquals(50, $user->fresh()->balance);
        $this->assertEquals('successful Withdrawal Transaction', $result['message']);
        $this->assertEquals(50, $result['balance']);
    }

    public function testGetBalance()
    {
        $user = User::factory()->create(['balance' => 100]);

        $balance = $this->atmService->getBalance($user);

        $this->assertEquals(100, $balance);
    }

    public function testGetTransactions()
    {
        $user = User::factory()->create();
        $transactions = Transaction::factory()->count(5)->create(['user_id' => $user->id]);

        $this->transactionRepo->expects($this->once())
            ->method('getTransactionsOfUser')
            ->with($user)
            ->willReturn($transactions);

        $result = $this->atmService->getTransactions($user);

        $this->assertCount(5, $result);
        $this->assertEquals($transactions->pluck('id'), $result->pluck('id'));
    }
}
