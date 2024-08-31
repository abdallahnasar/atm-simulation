<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepo = new UserRepository(new User());
    }

    public function testAll()
    {
        User::factory()->count(5)->create();
        $users = $this->userRepo->all();

        $this->assertCount(5, $users);
    }

    public function testAllPaginated()
    {
        User::factory()->count(15)->create();
        $users = $this->userRepo->allPaginated();

        $this->assertCount(10, $users);
    }

    public function testFind()
    {
        $user = User::factory()->create();
        $foundUser = $this->userRepo->find($user->id);

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testCreate()
    {
        $userData = [
            'name' => 'John Doe',
            'debit_card_number' => '1234567812345678',
            'pin' => '1234',
        ];

        $user = $this->userRepo->create($userData);

        $this->assertDatabaseHas('users', ['debit_card_number' => '1234567812345678']);
        $this->assertTrue(Hash::check('1234', $user->pin));
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $updateData = ['name' => 'Jane Doe'];

        $updated = $this->userRepo->update($user, $updateData);

        $this->assertTrue($updated);
        $this->assertEquals('Jane Doe', $user->fresh()->name);
    }

    public function testDelete()
    {
        $user = User::factory()->create();
        $deleted = $this->userRepo->delete($user->id);

        $this->assertEquals($deleted, 1);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
