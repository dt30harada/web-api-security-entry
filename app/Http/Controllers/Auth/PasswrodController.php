<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        if (Hash::check($request->password, $request->user()->password)) {
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
            'password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'max:255', 'regex:/\A[0-9a-z]++\z/ui'],
        ]);

        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => 'Invalid password.',
            ]);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json();
    }
}
