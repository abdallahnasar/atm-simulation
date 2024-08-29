<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\LoginService;
use Mockery;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    protected $loginService;
    protected $userModel;
    protected $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userModel = Mockery::mock(User::class);
        $this->hasher = Mockery::mock('Illuminate\Contracts\Hashing\Hasher');

        $this->loginService = new LoginService($this->userModel, $this->hasher);
    }

    public function testLoginSuccess()
    {
        $debitCardNumber = '1234567890123456';
        $pin = '1234';

        $this->userModel->shouldReceive('where')
            ->with('debit_card_number', $debitCardNumber)->andReturnSelf();

        $this->userModel->shouldReceive('first')
            ->andReturn($this->userModel);

        $this->userModel->shouldReceive('getAttribute')
            ->with('pin')->andReturn('hashed_pin');

        $this->hasher->shouldReceive('check')
            ->with($pin, 'hashed_pin')->andReturnTrue();

        $this->userModel->shouldReceive('createToken')
            ->andReturn((object) ['accessToken' => 'some_token']);

        $token = $this->loginService->login($debitCardNumber, $pin);

        $this->assertEquals('some_token', $token);
    }

    public function testLoginFailureInvalidCredentials()
    {
        $debitCardNumber = '1234567890';
        $pin = '1234';

        $this->userModel
            ->shouldReceive('where')
            ->with('debit_card_number', $debitCardNumber)
            ->andReturnSelf();

        $this->userModel
            ->shouldReceive('first')
            ->andReturn($this->userModel);

        $this->userModel
            ->shouldReceive('getAttribute')
            ->with('pin')
            ->andReturn('hashed_pin');

        $this->hasher
            ->shouldReceive('check')
            ->with($pin, 'hashed_pin')
            ->andReturnFalse();

        $token = $this->loginService->login($debitCardNumber, $pin);

        $this->assertNull($token);
    }

    public function testLoginFailureUserNotFound()
    {
        $debitCardNumber = '1234567890';
        $pin = '1234';

        $this->userModel
            ->shouldReceive('where')
            ->with('debit_card_number', $debitCardNumber)
            ->andReturnSelf();

        $this->userModel
            ->shouldReceive('first')
            ->andReturn(null);

        $token = $this->loginService->login($debitCardNumber, $pin);

        $this->assertNull($token);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
