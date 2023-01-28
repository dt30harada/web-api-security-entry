<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class AuthenticatedSessionController extends Controller
{
    /**
     * ログイン処理を実行する
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'Already logged in.',
            ]);
        }

        $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->whereRaw(sprintf("login_id = '%s'", $request->login_id))
            ->first();

        if ($user === null || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login_id' => 'Invalid credentials.',
            ]);
        }

        Auth::login($user);

        return response()->json([
            'message' => 'Logged in.',
        ]);
    }

    /**
     * ログアウト処理を実行する
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::logout();

        // セッションを破棄
        $request->session()->invalidate();

        // CSRFトークンを再生成
        $request->session()->regenerateToken();

        return response()->json();
    }
}
