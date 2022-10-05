<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\Auth\AuthController;

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

Route::group(
    [
        'prefix' => 'v1'
    ],
    function () {
        Route::post('users/login', [AuthController::class, 'login']);

        Route::group(
            ['middleware' => ['admin']],
            function () {
                Route::resource('users', UserController::class);
            }
        );

        Route::group(
            ['middleware' => ['product_owner']],
            function () {
                Route::post('projects', [ProjectController::class, 'store']);
                Route::post('tasks', [TaskController::class, 'store']);
            }
        );

        Route::resource('projects', ProjectController::class)->only([
            'index', 'show', 'destroy', 'update'
        ]);

        Route::resource('tasks', TaskController::class)->only([
            'index', 'show', 'destroy', 'update'
        ]);
    }
);
