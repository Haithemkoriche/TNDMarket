<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\FormTypeController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\CentreController;

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

// Routes d'authentification (publiques)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/product/{product}', [ProductController::class, 'show']);
Route::get('/products/all', [ProductController::class, 'showAll']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/product/{id}/reviews', [ProductController::class, 'reviews']);
Route::post('/product/{id}/reviews', [ProductController::class, 'storeReview']);
// Routes protégées par l'authentification
Route::middleware('auth:sanctum')->group(function () {

    // Routes d'authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Routes pour les formulaires (centres uniquement)
    Route::middleware('role:centre')->group(function () {
        Route::get('/forms', [FormController::class, 'index']);
        Route::post('/forms', [FormController::class, 'store']);
        Route::get('/forms/{form}', [FormController::class, 'show']);
        Route::get('/forms/{form}/pdf', [FormController::class, 'generatePdf']);
    });

    // Route pour obtenir les types de formulaires (accessible à tous les utilisateurs authentifiés)
    Route::get('/form-types', [FormController::class, 'getFormTypes']);

    // Routes pour les produits
    Route::apiResource('products', ProductController::class);

    // Routes pour les commandes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Routes d'administration (admin uniquement)
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Dashboard
        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::get('/activities', [DashboardController::class, 'activities']);

        // Gestion des types de formulaires
        Route::apiResource('form-types', FormTypeController::class);

        // Gestion des abonnements
        Route::apiResource('subscriptions', SubscriptionController::class);

        // Gestion des centres
        Route::get('/centres', [CentreController::class, 'index']);
        Route::put('/centres/{centre}', [CentreController::class, 'update']);
        Route::delete('/centres/{centre}', [CentreController::class, 'destroy']);
    });
});

// Route de test pour vérifier que l'API fonctionne
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now()->toISOString()
    ]);
});
