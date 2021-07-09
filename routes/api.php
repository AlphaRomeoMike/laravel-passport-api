<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Unvalidated Requests
 */
Route::post('register', [AuthController::class, 'Register']);
Route::post('login', [AuthController::class, 'Login']);
Route::get('find/{id}', [ProductController::class, 'Find']);


/**
 * @group Middleware API
 */
Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'Logout']);
    Route::post('product', [ProductController::class, 'Store']);
    Route::patch('product/{id}', [ProductController::class, 'Update']);
    Route::patch('product/{id}', [ProductController::class, 'Destroy']);
});