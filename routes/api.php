<?php
use App\Http\Controllers\HealthController;
use App\Http\Controllers\TokenAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServingController;
use App\Http\Controllers\QueuesController;
use App\Http\Controllers\WaitingController;
use App\Http\Controllers\UserSection;
use App\Http\Controllers\ServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/health', HealthController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin protected example
Route::get('/admin/metrics', fn () => ['ok' => true])
    ->middleware(['auth:sanctum', 'role:admin']);

// Health check
// Route::get('/health', fn () => ['ok' => true]);

// ---------- TOKEN AUTH ----------
Route::post('/token/login', [TokenAuthController::class, 'login'])
    ->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/token/logout', [TokenAuthController::class, 'logout']);
    Route::post('/token/logout-all', [TokenAuthController::class, 'logoutAll']);

    // Authenticated user details
    Route::get('/me', fn (Request $request) => $request->user());

    // Queue operations for user section
    Route::get('/queues/waiting/me', [QueuesController::class, 'waitingForMySection']);
    Route::get('/queues/serving/me', [QueuesController::class, 'servingForMySection']);
    Route::put('/queues/{id}/status', [QueuesController::class, 'updateStatus']);
    Route::put('/queues/{id}/cancel', [QueuesController::class, 'cancel']);
    Route::put('/queues/{id}/done', [QueuesController::class, 'done']);
    Route::put('/queues/{id}/transfer', [QueuesController::class, 'transfer']);

    // Services list (protected)
    Route::get('/services', [ServicesController::class, 'index']);
});

// ---------- PUBLIC ROUTES ----------

// Users Section
Route::get('/users_data', [UserSection::class, 'index']);
Route::post('/users_data/create', [UserSection::class, 'store']);

// Queues
Route::post('/queues/create', [QueuesController::class, 'store']);
Route::get('/queues', [WaitingController::class, 'index']);
Route::get('/queues/waiting', [QueuesController::class, 'waiting']);

// Clients
Route::post('/clients', [ClientController::class, 'create']);

// Serving
Route::get('/serving', [ServingController::class, 'index']);
Route::put('/serving/{id}/status', [ServingController::class, 'updateStatus']);

// Waiting
Route::get('/waiting', [WaitingController::class, 'index']);
Route::get('/waiting/{id}', [WaitingController::class, 'show']);
Route::put('/waiting/{id}', [WaitingController::class, 'proceed']);
Route::put('/waiting/{id}/status', [WaitingController::class, 'updateStatus']);
Route::put('/waiting/{id}/cancel', [WaitingController::class, 'cancel']);
