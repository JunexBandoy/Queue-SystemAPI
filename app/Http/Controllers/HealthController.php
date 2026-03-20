<?php

namespace App\Http\Controllers;

class HealthController extends Controller
{
    /**
     * Health check endpoint
     *
     * @OA\Get(
     *   path="/api/health",
     *   summary="Health check",
     *   tags={"System"},
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="ok", type="boolean", example=true)
     *     )
     *   )
     * )
     */
    public function __invoke()
    {
        return response()->json(['ok' => true]);
    }
}
