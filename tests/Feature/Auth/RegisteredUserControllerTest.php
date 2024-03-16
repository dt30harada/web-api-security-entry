<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 正常にユーザーを登録できる()
    {
        $request = [
            'login_id' => 'admin',
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/auth/register', $request);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'login_id' => $request['login_id'],
            'password' => md5($request['password']),
        ]);
    }

    /**
     * @test
     */
    public function 正常にログインユーザー情報を取得できる()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->createOne([
            'login_id' => 'admin',
            'password' => '12345678',
        ]);

        $response = $this->actingAs($user)->getJson('/api/auth/user');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $user->id,
                    'login_id' => $user->login_id,
                ],
            ]);
    }
}
