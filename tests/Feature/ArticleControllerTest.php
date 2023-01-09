<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne([
            'login_id' => 'admin',
            'password' => Hash::make('12345678'),
        ]);
    }

    /**
     * @test
     */
    public function 正常に記事を登録できる()
    {
        $request = [
            'title' => 'サンプルタイトル',
            'body' => "サンプル本文\nテスト",
        ];

        $response = $this
            ->actingAs($this->user)
            ->getJson('/api/articles?'.http_build_query($request));

        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'title' => $request['title'],
            'body' => $request['body'],
        ]);
    }

    /**
     * @test
     */
    public function 正常に記事一覧を取得できる()
    {
        Article::factory()->createMany([
            [
                'user_id' => $this->user->id,
                'title' => 'サンプルタイトル',
                'body' => 'test1',
            ],
            [
                'user_id' => $this->user->id,
                'title' => 'テストタイトル',
                'body' => 'test3',
            ],
        ]);

        $request = [
            'per_page' => 20,
            'page' => 1,
            'title' => 'テスト',
        ];

        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/articles/query', $request);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'current_page',
                    'from',
                    'to',
                    'last_page',
                    'per_page',
                    'total',
                    'items' => [
                        '*' => [
                            'id',
                            'title',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ])
            ->assertJsonPath('data.total', 1)
            ->assertJsonPath('data.items.0.title', 'テストタイトル');
    }

    /**
     * @test
     */
    public function 正常に記事詳細を取得できる()
    {
        $article = Article::factory()->createOne([
            'user_id' => $this->user->id,
            'title' => 'サンプルタイトル',
            'body' => '本文テスト',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getJson("/api/articles/{$article->id}");

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'title',
                    'body',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'id' => $article->id,
                    'user_id' => $article->user_id,
                    'title' => $article->title,
                    'body' => $article->body,
                ],
            ]);
    }

    /**
     * @test
     */
    public function 正常に記事を更新できる()
    {
        $article = Article::factory()->createOne([
            'user_id' => $this->user->id,
            'title' => 'サンプルタイトル',
            'body' => '本文テスト',
        ]);
        $request = [
            'title' => '更新タイトル',
            'body' => "更新\n本文テスト",
        ];

        $response = $this
            ->actingAs($this->user)
            ->putJson("/api/articles/{$article->id}", $request);

        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $request['title'],
            'body' => $request['body'],
        ]);
    }

    /**
     * @test
     */
    public function 正常に記事削除できる()
    {
        $article = Article::factory()->createOne([
            'user_id' => $this->user->id,
            'title' => 'サンプルタイトル',
            'body' => '本文テスト',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }
}
