<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class PasswrodController extends Controller
{
    /**
     * パスワードを確認する
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $match = $request->user()->where([
            'login_id' => $request->user()->login_id,
            'password' => md5($request->password),
        ])->exists();

        if ($match) {
            return response()->json();
        }

        throw ValidationException::withMessages([
            'password' => 'Invalid password.',
        ]);
    }

    /**
     * パスワードを更新する
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'max:255', 'regex:/\A[0-9a-z]++\z/ui'],
        ]);

        $attributes = $request->all();
        $attributes['password'] = md5($request->password);

        $request->user()->update($attributes);

        return response()->json();
    }
}
