<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;


// Rotas abertas
Route::post('login', [AuthController::class, 'login']);
Route::post('/verify-email', [EmailVerificationController::class, 'verify']);

Route::get('/proposals/signature/{token}', [ProposalController::class, 'showSignaturePageData']);


// Rotas protegidas pela middleware
Route::middleware('auth:api')->group(function () {

   
     // Rota para obter informacoes do user autenticado
     Route::get('me', [AuthController::class, 'me']);


     Route::prefix('users')->as('users.')->group(function () {
         Route::get('/', [UserController::class, 'index'])->name('index');
         Route::post('register', [UserController::class, 'store'])->name('register');
         Route::get('{id}', [UserController::class, 'show']);
         Route::put('{id}', [UserController::class, 'update']);
     });
 
     Route::prefix('/dashboard')->group( function() {
         Route::get('/', [DashboardController::class, 'index']);
     });
 
     Route::get('/emails', [EmailController::class, 'index']);
 
     Route::prefix('products')->group(function () {
         Route::get('/', [ProductController::class, 'index']);
         Route::get('{id}', [ProductController::class, 'show']);
     });
 
     Route::prefix('clients')->group(function () {
         Route::get('/', [ClientController::class, 'index']);
         Route::post('/', [ClientController::class, 'store']);
         Route::delete('{id}', [ClientController::class, 'destroy']);
     });
 
     Route::prefix('proposals')->group(function() {
         Route::get('/', [ProposalController::class, 'index']);
         Route::get('{id}', [ProposalController::class, 'show']);
         Route::post('/', [ProposalController::class, 'store']);
         Route::patch('{id}', [ProposalController::class, 'update']);
         Route::post('{id}/send-signature-link', [ProposalController::class, 'sendSignatureLink']);
    });

});
