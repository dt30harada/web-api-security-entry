<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class RegisteredUserController extends Controller
{
    /**
     * ユーザーを登録する
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'login_id' => ['required', 'string', 'max:10', 'unique:'.User::class],
            'password' => ['required', 'string', 'max:255', 'regex:/\A[0-9a-z]\z/ui'],
        ]);

        $user = User::create([
            'login_id' => $request->login_id,
            'password' => md5($request->password),
        ]);

        Auth::login($user);

        return response()->json(status: JsonResponse::HTTP_CREATED);
    }

    /**
     * ユーザー情報を取得する
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'login_id' => $user->login_id,
            ],
        ]);
    }
}
