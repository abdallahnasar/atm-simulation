<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testLoginReturnsTokenOnSuccess()
    {
        $user = User::factory()->create([
            'debit_card_number' => '1234567812345678',
            'pin' => Hash::make('1234')
        ]);

        Passport::actingAs($user);

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id,
            'Test Personal Access Client',
            'http://localhost'
        );

        $token = $user->createToken('TestToken', ['*'])->accessToken;

        $this->withHeader('Authorization', 'Bearer '.$token);

        $response = $this->postJson('/api/login', [
            'debit_card_number' => '1234567812345678',
            'pin' => '1234'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['token'],
                'message',
            ]);
    }

    public function testLoginReturnsValidationError()
    {
        $user = User::factory()->create([
            'debit_card_number' => '1234567812345678',
            'pin' => Hash::make('1234')
        ]);

        Passport::actingAs($user);

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id,
            'Test Personal Access Client',
            'http://localhost'
        );

        $token = $user->createToken('TestToken', ['*'])->accessToken;

        $this->withHeader('Authorization', 'Bearer '.$token);

        $response = $this->postJson('/api/login', [
            'debit_card_number' => '1234567812345678',
            'pin' => 'wrong-pin'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => ['pin' => ['The pin field must be 4 characters.']],
        ]);
    }

    public function testLoginFailureUserNotFound()
    {
        $data = [
            'debit_card_number' => '1234567812345677',
            'pin' => '1234',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);
    }

}
