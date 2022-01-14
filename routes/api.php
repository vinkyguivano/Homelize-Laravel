<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\RoomController;
use App\Models\ProjectImage;
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

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'design'], function() {
    Route::get('/filters', [ProjectImageController::class, 'getFilters']);
    Route::get('/images', [ProjectImageController::class, 'getImages']);
    Route::get('/images/{id}', [ProjectImageController::class, 'getImageDetail']);
    Route::get('/rooms', [RoomController::class, 'getRooms']);
    Route::post('/user/images', [ProjectImageController::class, 'createUserImage']);
    Route::delete('/user/images/{id}', [ProjectImageController::class, 'deleteUserImage']);
    Route::get('/user/images', [ProjectImageController::class, 'getUserImages']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'professional'], function() {
    Route::get('/professionals', [ProfessionalController::class, 'getList']);
    Route::get('/professionals/{id}', [ProfessionalController::class, 'getDetail']);
    Route::get('/projects/{id}', [ProjectController::class, 'getDetail']);
    Route::get('/professionals/{id}/review', [ProfessionalController::class, 'getRating']);
    Route::get('/cities', [ProfessionalController::class, 'getCities']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'orders'], function() {
    Route::post('/', [OrderController::class, 'createOrder']);
    Route::post('/{id}/images', [OrderController::class, 'uploadImage']);
    Route::get('/', [OrderController::class, 'getOrderList']);
    Route::get('/{order}', [OrderController::class, 'getOrderDetail']);
    Route::post('/{order}/update', [OrderController::class, 'updateOrder']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v1'], function() {
    Route::group(['prefix' => 'professionals'], function() {
        Route::post('/{id}/update', [ProfessionalController::class, 'updateProfessional']);
        Route::post('/{id}/image', [ProfessionalController::class, 'uploadImage']);
        Route::post('/{id}/projects', [ProjectController::class, 'addProject']);
        Route::post("/{id}/profile-completion", [ProfessionalController::class, 'profileComplete']);
    });
    Route::delete('/projects/{id}', [ProjectController::class, 'deleteProject']);
    Route::put('/projects/{id}', [ProjectController::class, 'updateProject']);
    Route::group(['prefix' => 'images'], function() {
        Route::post('/{id}', [ProjectImageController::class, 'addProjectImage']);
    });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/{provider}', [AuthController::class,'SocialLogin']);
Route::post('/professional/register', [AuthController::class, 'registerProfessional']);
Route::post('/professional/login', [AuthController::class, 'loginProfessional']);
Route::post('test', function(Request $request) {
    return response(["Hello" => $request->has('name')],200);
});
Route::post('test/upload-photo', [OrderController::class, 'UploadPhoto']);
