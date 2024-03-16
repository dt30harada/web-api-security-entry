<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswrodControllerTest extends TestCase
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
            'password' => self::PASSWORD,
            'new_password' => 'newpassword',
        ];

        $response = $this
            ->actingAs($this->user)
            ->putJson('/api/auth/password', $request);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check($request['password'], $this->user->password));
    }
}
