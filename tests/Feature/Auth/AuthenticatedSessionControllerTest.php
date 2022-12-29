<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    private readonly User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne([
            'login_id' => 'admin',
            'password' => md5('123456'),
        ]);
    }

    /**
     * @test
     */
    public function 正常にログインできる()
    {
        $request = [
            'login_id' => 'admin',
            'password' => '123456',
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
