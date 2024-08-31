<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ATMService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ATMControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $atmService;

    public function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'debit_card_number' => '1234567812345678',
            'pin' => Hash::make('1234')
        ]);

        // Set up Passport and create a client
        Passport::actingAs($this->user);
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $this->user->id,
            'Test Personal Access Client',
            'http://localhost'
        );

        // Create a token
        $this->token = $this->user->createToken('TestToken', ['*'])->accessToken;

        // Set up the ATM service
        $this->atmService = $this->app->make(ATMService::class);

        // Perform login
        $this->postJson('/api/v1/login', [
            'debit_card_number' => '1234567812345678',
            'pin' => '1234'
        ]);
    }

    protected function authenticatedRequest($method, $uri, array $data = [])
    {
        return $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->json($method, $uri, $data);
    }

    public function testDeposit()
    {
        $amount = 100;

        $response = $this->authenticatedRequest('POST', '/api/v1/deposit', ['amount' => $amount]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['new_balance'],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'successful Deposit Transaction'
            ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => $amount,
            'type' => 'deposit'
        ]);
    }

    public function testWithdraw()
    {
        $this->atmService->deposit($this->user, 200);

        $amount = 50;

        $response = $this->authenticatedRequest('POST', '/api/v1/withdraw', ['amount' => $amount]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['new_balance'],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'successful Withdrawal Transaction'
            ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => $amount,
            'type' => 'withdrawal'
        ]);
    }

    public function testBalance()
    {
        $response = $this->authenticatedRequest('GET', '/api/v1/balance');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['balance'],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Balance retrieved successfully'
            ]);
    }

    public function testTransactions()
    {
        $this->atmService->deposit($this->user, 100);
        $this->atmService->withdraw($this->user, 50);

        $response = $this->authenticatedRequest('GET', '/api/v1/transactions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'amount', 'type', 'created_at']
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Transactions retrieved successfully'
            ]);

        $this->assertCount(2, $response->json('data'));
    }











    public function testWithdrawWithInsufficientFunds()
    {
        $response = $this->authenticatedRequest('POST', '/api/v1/withdraw', ['amount' => 987654321]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => ['amount'=>['Insufficient funds']]
            ]);
    }

    public function testDepositWithNegativeAmount()
    {
        $response = $this->authenticatedRequest('POST', '/api/v1/deposit', ['amount' => -100]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => ['amount'=>['The amount field must be at least 0.01.']]
            ]);
    }

    public function testWithdrawWithNegativeAmount()
    {
        $response = $this->authenticatedRequest('POST', '/api/v1/withdraw', ['amount' => -100]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => ['amount'=>['The amount field must be at least 0.01.']]
            ]);
    }

    public function testDepositWithNonNumericAmount()
    {
        $response = $this->authenticatedRequest('POST', '/api/v1/deposit', ['amount' => 'abc']);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => [
                    'amount'=>[
                        'The amount field must be a number.'
                    ]
                ]
            ]);
    }

    public function testWithdrawWithNonNumericAmount()
    {
        $response = $this->authenticatedRequest('POST', '/api/v1/withdraw', ['amount' => 'abc']);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => [
                    'amount'=>[
                        'The amount field must be a number.',
                        'Insufficient funds'
                    ]
                ]
            ]);
    }

    public function testDepositWithoutAmount()
    {
        $response = $this->authenticatedRequest('POST', '/api/v1/deposit', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => [
                    'amount'=>[
                        'The amount field is required.'
                    ]
                ]
            ]);
    }

    public function testWithdrawWithoutAmount()
    {
        $response = $this->authenticatedRequest('POST', '/api/v1/withdraw', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => [
                    'amount'=>[
                        'The amount field is required.'
                    ]
                ]
            ]);
    }
}
