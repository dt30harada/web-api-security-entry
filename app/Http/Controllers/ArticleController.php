<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\StoreRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class ArticleController extends Controller
{
    /**
     * 記事を投稿する
     *
     * @param  StoreRequest  $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $article = $request->user()->articles()->create(
            $request->only('title', 'body')
        );

        return response()->json([
            'data' => [
                'id' => $article->id,
            ],
        ]);
    }

    /**
     * 記事を検索する
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function query(Request $request): JsonResponse
    {
        $request->validate([
            'per_page' => ['required', 'integer', 'max:200'],
            'page' => ['required', 'integer'],
            'title' => ['string', 'max:50'],
        ]);

        $query = DB::table('articles as a')
            ->join('users as u', 'a.user_id', 'u.id')
            ->orderByDesc('a.created_at');

        if ($request->has('title')) {
            $query->whereRaw("title collate utf8mb4_0900_ai_ci like '%{$request->title}%'");
        }

        $paginator = $query->paginate($request->per_page, [
            'a.id',
            'u.login_id as user_login_id',
            'a.title',
            'a.created_at',
            'a.updated_at',
        ]);

        return response()->json([
            'data' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem() ?? 0,
                'to' => $paginator->lastItem() ?? 0,
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'items' => $paginator->items(),
            ],
        ]);
    }

    /**
     * 記事詳細を取得する
     *
     * @param  Request  $request
     * @param  Article  $article
     * @return JsonResponse
     */
    public function show(Request $request, Article $article): JsonResponse
    {
        $_article = $article->toArray();

        return response()->json([
            'data' => [
                'id' => $_article['id'],
                'user_id' => $_article['user_id'],
                'user_login_id' => $article->user->login_id,
                'is_owner' => $request->user()->id === $article->user_id,
                'title' => $_article['title'],
                'body' => $_article['body'],
                'created_at' => $_article['created_at'],
                'updated_at' => $_article['updated_at'],
            ],
        ]);
    }

    /**
     * 記事を更新する
     *
     * @param  StoreRequest  $request
     * @param  Article  $article
     * @return JsonResponse
     */
    public function update(StoreRequest $request, Article $article): JsonResponse
    {
        $article->update($request->only('title', 'body'));

        return response()->json();
    }

    /**
     * 記事を削除する
     *
     * @param  Article  $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json();
    }
}
