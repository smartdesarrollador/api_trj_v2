<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ConfiguracionesController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\DigitalCardController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('/', function () {
    return response()->json(['message' => 'Hola Mundo']);
});

// Endpoint público para acceder a imágenes de perfil
Route::get('users/profile-image/{userId}', [AuthController::class, 'getProfileImage']);

// Rutas públicas para configuraciones
Route::get('configuraciones/todas', [ConfiguracionesController::class, 'obtenerTodas']);
Route::get('configuraciones/imagen/{clave}', [ConfiguracionesController::class, 'obtenerImagen']);

// Rutas públicas para banners
Route::get('banners', [BannerController::class, 'index']);
Route::get('banners/{id}', [BannerController::class, 'show']);

// Rutas públicas para tarjetas digitales
Route::get('tarjetas/{slug}', [DigitalCardController::class, 'getBySlug']);


// Rutas públicas de autenticación
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    // Rutas de recuperación de contraseña
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('validate-reset-token', [AuthController::class, 'validateResetToken']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Rutas protegidas que requieren autenticación
Route::group(['middleware' => 'jwt.verify'], function () {
    // Rutas de autenticación que requieren estar autenticado
    Route::group(['prefix' => 'auth'], function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::put('change-password', [AuthController::class, 'changePassword']);
        Route::post('profile-image', [AuthController::class, 'uploadProfileImage']);
        // Ruta para verificar si un usuario tiene acceso al panel admin
        Route::get('check-admin-access', [UserController::class, 'checkAdminAccess']);
    });
    
    // Rutas de usuarios
    Route::apiResource('users', UserController::class);
    
    // Rutas para gestión del perfil
    Route::put('users/profile', [UserController::class, 'updateProfile']);
    Route::put('users/password', [UserController::class, 'updatePassword']);
    Route::post('users/profile-image', [UserController::class, 'uploadProfileImage']);

    // Rutas para configuraciones (protegidas, solo para administradores)
    Route::prefix('configuraciones')->group(function () {
        Route::get('/', [ConfiguracionesController::class, 'index']);
        Route::get('/grupos', [ConfiguracionesController::class, 'grupos']);
        Route::get('/{id}', [ConfiguracionesController::class, 'show']);
        Route::put('/{id}', [ConfiguracionesController::class, 'update']);
        Route::post('/{id}/imagen', [ConfiguracionesController::class, 'subirImagen']);
        Route::post('/actualizar-multiple', [ConfiguracionesController::class, 'actualizarMultiple']);
    });

    // Rutas para gestión de banners (protegidas, solo para administradores)
    Route::prefix('admin/banners')->group(function () {
        Route::get('/', [BannerController::class, 'admin']);
        Route::post('/{id}', [BannerController::class, 'update']);
    });
    Route::apiResource('banners', BannerController::class)->except(['index', 'show', 'update']);
    
    // Rutas para tarjetas digitales (protegidas, requieren autenticación)
    Route::prefix('digital-cards')->group(function () {
        Route::get('/', [DigitalCardController::class, 'index']);
        Route::post('/', [DigitalCardController::class, 'store']);
        Route::get('/{digital_card}', [DigitalCardController::class, 'show']);
        Route::put('/{digital_card}', [DigitalCardController::class, 'update']);
        Route::post('/{digital_card}/update', [DigitalCardController::class, 'update']); // Nueva ruta POST para update
        Route::delete('/{digital_card}', [DigitalCardController::class, 'destroy']);
        Route::put('/{digital_card}/toggle-status', [DigitalCardController::class, 'toggleStatus']);
        Route::post('/{digital_card}/toggle-status', [DigitalCardController::class, 'toggleStatus']); // POST para toggle-status
        Route::post('/{digital_card}/upload-image', [DigitalCardController::class, 'uploadImage']);
        Route::delete('/{digital_card}/delete-image', [DigitalCardController::class, 'deleteImage']);
    });
});

/* Route::post('register',[UserController::class,'register']);

Route::post('login',[UserController::class,'login']); */

/* Route::apiResource('users', UserController::class); */














