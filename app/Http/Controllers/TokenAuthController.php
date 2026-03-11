<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;


class TokenAuthController extends Controller
{
       // Set to true to ensure only ONE active token per user at a time
private const REVOKE_OLD_TOKENS_ON_LOGIN = true;


/**
 * POST /api/token/login
 * Always issues a fresh token on successful login.
 */
public function login(Request $request)
{
    $credentials = $request->validate([
        'email'       => ['required', 'email'],
        'password'    => ['required', 'string'],
        'device_name' => ['nullable', 'string', 'max:255'], // label the token
    ]);

    if (!Auth::attempt([
        'email'    => $credentials['email'],
        'password' => $credentials['password']
    ])) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (self::REVOKE_OLD_TOKENS_ON_LOGIN) {
        // revoke all old tokens
        $user->tokens()->delete();
    }

    $deviceName = $credentials['device_name']
        ?? ($request->userAgent() ?? 'api-client');

    // Create a brand-new token on each login
    $plainTextToken = $user
        ->createToken($deviceName, ['*'])
        ->plainTextToken;

    return response()->json([
        'token'      => $plainTextToken, // Use: Authorization: Bearer <token>
        'token_type' => 'Bearer',
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'section_id' => $user->section_id,
            'email' => $user->email,
            'role'  => $user->role,
        ],
    ], 201);
}


/**
 * POST /api/token/logout
 * Revokes only the token used in this request.
 */


 public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $current = $user->currentAccessToken();

        // Case 1: Bearer token (PersonalAccessToken) -> revoke it
        if ($current instanceof PersonalAccessToken) {
            $current->delete();
            return response()->noContent(); // 204
        }

        // Case 2: Session/cookie auth (TransientToken) -> perform session logout
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }



/**
 * POST /api/token/logout-all
 * Revokes ALL tokens for this user.
 */
public function logoutAll(Request $request)
{
    $request->user()?->tokens()->delete();

    return response()->noContent();
}
}
