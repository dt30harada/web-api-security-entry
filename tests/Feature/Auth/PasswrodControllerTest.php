<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswrodControllerTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = '123456';

    private readonly User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne([
            'login_id' => 'admin',
            'password' => md5(self::PASSWORD),
        ]);
    }

    /**
     * @test
     */
    public function 正常にパスワードを確認できる()
    {
        $request = [
            'password' => self::PASSWORD,
        ];

        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/auth/confirm-password', $request);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function 正常にパスワードを更新できる()
    {
        $request = [
            'password' => 'new-password',
        ];

        $response = $this
            ->actingAs($this->user)
            ->putJson('/api/auth/password', $request);

        $response->assertStatus(200);
        $this->assertSame(md5($request['password']), $this->user->password);
    }
}
