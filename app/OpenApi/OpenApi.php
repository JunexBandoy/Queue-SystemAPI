<?php

namespace App\OpenApi;

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Queue System API",
 *     version="1.0.0",
 *     description="API documentation for the Queue System"
 *   ),
 *   @OA\Server(
 *     url="/",
 *     description="Current server"
 *   ),
 *   @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Use format: Bearer {token}"
 *   )
 * )
 */
class OpenApi
{
}