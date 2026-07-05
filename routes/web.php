<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Manager\HotelController as ManagerHotelController;
use App\Http\Controllers\Manager\RoomController as ManagerRoomController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\HotelController as PublicHotelController;
use App\Http\Controllers\Public\CompareController;
use App\Http\Controllers\Public\ReviewController as PublicReviewController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// ROTAS PÚBLICAS — qualquer visitante
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('hoteis')->name('hotels.')->group(function () {
    Route::get('/',                        [PublicHotelController::class, 'index'])->name('index');
    Route::get('/comparar',               [CompareController::class, 'index'])->name('compare');
    Route::get('/{slug}',                 [PublicHotelController::class, 'show'])->name('show');
});

// ─────────────────────────────────────────────────────────────────────────────
// ROTAS AUTENTICADAS — qualquer utilizador com login
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    // Avaliações (apenas guests autenticados)
    Route::prefix('hoteis/{hotel}')->name('hotels.')->group(function () {
        Route::post('/avaliacoes',        [PublicReviewController::class, 'store'])->name('reviews.store');
        Route::delete('/avaliacoes/{review}', [PublicReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    // Redireccionamento após login conforme o role
    Route::get('/dashboard', function () {
        return match(auth()->user()->role) {
            'admin'         => redirect()->route('admin.dashboard'),
            'hotel_manager' => redirect()->route('manager.dashboard'),
            default         => redirect()->route('home'),
        };
    })->name('dashboard');
});

// ─────────────────────────────────────────────────────────────────────────────
// PAINEL DO GESTOR DE HOTEL
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:hotel_manager'])
    ->prefix('gestor')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard',          [ManagerDashboard::class, 'index'])->name('dashboard');

        // Hotel do gestor
        Route::get('/meu-hotel',          [ManagerHotelController::class, 'index'])->name('hotel.index');
        Route::get('/meu-hotel/editar',   [ManagerHotelController::class, 'edit'])->name('hotel.edit');
        Route::put('/meu-hotel',          [ManagerHotelController::class, 'update'])->name('hotel.update');
        Route::post('/meu-hotel/imagens', [ManagerHotelController::class, 'uploadImages'])->name('hotel.images.upload');
        Route::delete('/meu-hotel/imagens/{image}', [ManagerHotelController::class, 'deleteImage'])->name('hotel.images.delete');

        // Quartos
        Route::resource('quartos', ManagerRoomController::class)->except(['show']);

        // Disponibilidade em tempo real
        Route::patch('/quartos/{room}/disponibilidade', [ManagerRoomController::class, 'toggleAvailability'])->name('rooms.availability');
    });

// ─────────────────────────────────────────────────────────────────────────────
// PAINEL DO ADMINISTRADOR
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard',          [AdminDashboard::class, 'index'])->name('dashboard');

        // Gestão de hotéis
        Route::resource('hoteis', AdminHotelController::class)->parameters(['hoteis' => 'hotel']);
        Route::patch('/hoteis/{hotel}/status', [AdminHotelController::class, 'updateStatus'])->name('hotels.status');

        // Gestão de utilizadores
        Route::resource('utilizadores', AdminUserController::class)->parameters(['utilizadores' => 'utilizador']);
        Route::patch('/utilizadores/{utilizador}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');

        // Moderação de avaliações
        Route::get('/avaliacoes',         [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/avaliacoes/{review}/aprovar',  [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::patch('/avaliacoes/{review}/rejeitar', [AdminReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/avaliacoes/{review}',         [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    });