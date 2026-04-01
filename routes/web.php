<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - GooCRM
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Protected Routes (ERP Core)
 * Todas as rotas abaixo exigem autenticação e verificação de e-mail.
 */
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard - Utilizando invokable controller
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    /**
     * Módulos Operacionais
     * Implementação via Resource para garantir o padrão CRUD e suporte a UUID.
     */
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);

    /**
     * Módulo Financeiro
     * Adicionado para resolver o erro RouteNotFoundException.
     */
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    /**
     * Profile Management
     */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');

        // Rotas de Avatar simplificadas dentro do grupo
        Route::patch('/profile/avatar', 'updateAvatar')->name('profile.avatar.update');
        Route::delete('/profile/avatar', 'destroyAvatar')->name('profile.avatar.destroy');
    });
});

/**
 * Authentication Routes (Breeze)
 */
require __DIR__ . '/auth.php';