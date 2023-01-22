<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Psr\Http\Message\ServerRequestInterface;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\UserController;

use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

Route::apiResource('customers', CustomerController::class);

Route::apiResource('users', UserController::class);

Route::post('/tokens/create', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response()->json([
        'token_type' => 'Bearer',
        'access_token' => $user->createToken('token_name')->plainTextToken // token name you can choose for your self or leave blank if you like to
    ]);
});

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
