<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserSection extends Controller
{
    /**
     * GET /api/users
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'data' => $users,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

    /**
     * POST /api/users
     */
    public function store(Request $request)
    {
        // Validation - Laravel will return 422 JSON on failure automatically
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password'   => ['required', 'string', 'min:8'],
            // Adjust the table/column to match your schema
            'section_id' => ['required', 'integer', 'max:6'],
            // Validate role but DO NOT mass-assign it
            'role'       => ['sometimes', 'string', 'max:255'],
        ]);

        try {
            // Create user - password is hashed by model mutator
            $user = User::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'password'   => $validated['password'],
                'section_id' => $validated['section_id'],
            ]);

            // If you truly need to set role (trusted context only), do it explicitly:
            // if (isset($validated['role'])) {
            //     $user->role = $validated['role'];
            //     $user->save();
            // }

            return response()->json([
                'data' => $user->fresh(),
                'isSuccess' => true,
                'errorMessages' => []
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'isSuccess' => false,
                'error' => 'An unexpected error occurred.'
                // In production, avoid exposing $e->getMessage()
            ], 500);
        }
    }
}
