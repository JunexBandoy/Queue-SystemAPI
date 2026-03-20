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
     *
     * @OA\Post(
     *   path="/api/token/login",
     *   tags={"Auth"},
     *   summary="Login and receive a new Bearer token",
     *   description="Validates credentials and returns a fresh Sanctum token. Old tokens may be revoked depending on server policy.",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *       @OA\Property(property="password", type="string", format="password", example="secret"),
     *       @OA\Property(property="device_name", type="string", nullable=true, example="api-client")
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Token issued",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="token", type="string", example="1|eyJ0eXAiOiJKV1QiLCJhbGciOiJI..."),
     *       @OA\Property(property="token_type", type="string", example="Bearer"),
     *       @OA\Property(
     *         property="user",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="name", type="string", example="Juan Dela Cruz"),
     *         @OA\Property(property="section_id", type="integer", nullable=true, example=3),
     *         @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *         @OA\Property(property="role", type="string", example="admin")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=401, description="Invalid credentials"),
     *   @OA\Response(response=422, description="Validation failed"),
     *   @OA\Response(response=429, description="Too many attempts")
     * )
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
                'id'         => $user->id,
                'name'       => $user->name,
                'section_id' => $user->section_id,
                'email'      => $user->email,
                'role'       => $user->role,
            ],
        ], 201);
    }

    /**
     * POST /api/token/logout
     * Revokes only the token used in this request.
     *
     * @OA\Post(
     *   path="/api/token/logout",
     *   tags={"Auth"},
     *   summary="Logout current token",
     *   description="Revokes only the Bearer token sent in this request.",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=204, description="Logged out (no content)"),
     *   @OA\Response(response=401, description="Unauthenticated")
     * )
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
     *
     * @OA\Post(
     *   path="/api/token/logout-all",
     *   tags={"Auth"},
     *   summary="Logout all tokens for the current user",
     *   description="Revokes all issued tokens for the authenticated user.",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=204, description="All tokens revoked"),
     *   @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function logoutAll(Request $request)
    {
        $request->user()?->tokens()->delete();

        return response()->noContent();
    }
}