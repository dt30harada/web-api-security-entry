<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = '12345678';

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne([
            'login_id' => 'admin',
            'password' => Hash::make(self::PASSWORD),
        ]);
    }

    /**
     * @test
     */
    public function 正常にログインできる()
    {
        $request = [
            'login_id' => 'admin',
            'password' => self::PASSWORD,
        ];

        $response = $this->postJson('/api/auth/login', $request);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * @test
     */
    public function 正常にログアウトできる()
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $this->assertGuest();
    }
}
