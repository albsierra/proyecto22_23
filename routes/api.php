<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Psr\Http\Message\ServerRequestInterface;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AvatarController;
use App\Http\Controllers\API\ArtworkController;
use App\Http\Controllers\API\TokenController;

use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config\Config;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user();
    $user->fullName = $user->name;
    return $user;
});

Route::apiResource('customers', CustomerController::class)->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);

Route::apiResource('orders', OrderController::class)->middleware('auth:sanctum');

Route::get('artworks', [ArtworkController::class, 'index']);

// emite un nuevo token
Route::post('tokens', [TokenController::class, 'store']);
// elimina el token del usuario autenticado
Route::delete('tokens', [TokenController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('/avatars', [AvatarController::class, 'store'])->middleware('auth:sanctum');
Route::get('/avatars', [AvatarController::class, 'getAvatar'])->middleware('auth:sanctum');
Route::get('/avatars/{user_id}', [AvatarController::class, 'getUserAvatar']);

Route::any('/{any}', function (ServerRequestInterface $request) {
    $config = new Config([
        'address' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'basePath' => '/api',
    ]);
    $api = new Api($config);
    $response = $api->handle($request);

    try {
        $records = json_decode($response->getBody()->getContents())->records;
        $response = response()->json($records, 200, $headers = ['X-Total-Count' => count($records)]);
    } catch (\Throwable $th) {

    }
    return $response;

})->where('any', '.*');
