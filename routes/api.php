<?php
use App\Http\Controllers\TokenAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServingController;
use App\Http\Controllers\QueuesController;
use App\Http\Controllers\WaitingController;
use App\Http\Controllers\UserSection;
use App\Http\Controllers\ServicesController;
use App\Models\Waiting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Role-protected example (admin only)
Route::get('/admin/metrics',fn () => ['ok' => true])->middleware(['auth:sanctum','role:admin']);


// Health (optional)
Route::get('/health',fn () => ['ok' => true]);


// ---------- Token auth ----------
Route::post('/token/login',[TokenAuthController::class, 'login'])->middleware('throttle:10,1');   // basic rate limit


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/token/logout',[TokenAuthController::class, 'logout']);
    Route::post('/token/logout-all',[TokenAuthController::class, 'logoutAll']);

    // Example protected endpoint
    Route::get('/me',function (Request $request) {
            return $request->user();
        }
        
    );
   
   Route::get('/queues/waiting/me', [QueuesController::class, 'waitingForMySection']);
   Route::get('/queues/serving/me', [QueuesController::class, 'servingForMySection']);
   Route::put('queues/{id}/status', [QueuesController::class, 'updateStatus']);
   Route::put('queues/{id}/cancel', [QueuesController::class, 'cancel']);
   Route::put('queues/{id}/done', [QueuesController::class, 'done']);
   Route::put('queues/{id}/transfer', [QueuesController::class, 'transfer']);


   Route::get('services', [ServicesController::class, 'index']);


});

Route::get('users_data', [UserSection::class, 'index']);
Route::post('users_data/create', [UserSection::class, 'store']);

Route::post('queues/create', [QueuesController::class, 'store']);


Route::post('clients', [ClientController::class, 'create']);

Route::get('serving', [ServingController::class, 'index']);
Route::put('serving/{id}/status', [ServingController::class, 'updateStatus']);

Route::post('queues/create', [QueuesController::class, 'store']);
Route::get('queues', [WaitingController::class, 'index']);
Route::get('queues/waiting', [QueuesController::class, 'waiting']);



Route::get('waiting', [WaitingController::class, 'index']);
Route::get('waiting/{id}', [WaitingController::class, 'show']);
Route::put('waiting/{id}', [WaitingController::class, 'proceed']);
Route::put('waiting/{id}/status', [WaitingController::class, 'updateStatus']);
Route::put('waiting/{id}/cancel', [WaitingController::class, 'cancel']);