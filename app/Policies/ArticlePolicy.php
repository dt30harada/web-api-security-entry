<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * 記事の更新権限をチェックする
     *
     * @param User $user
     * @param Article $article
     * @return Response|bool
     */
    public function update(User $user, Article $article): Response|bool
    {
        return $user->id === $article->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * 記事の削除権限をチェックする
     *
     * @param User $user
     * @param Article $article
     * @return Response|bool
     */
    public function delete(User $user, Article $article): Response|bool
    {
        return $this->update($user, $article);
    }
}
