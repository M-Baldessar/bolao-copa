<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BolaoGroupController;
use App\Http\Controllers\ChampionPickController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

// Rotas para visitantes (não autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:5,1');

    Route::get('/esqueci-senha', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/esqueci-senha', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/redefinir-senha/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/redefinir-senha', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1');
});

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/grupos', [GroupController::class, 'index'])->name('groups.index');

    Route::get('/partidas', [MatchController::class, 'index'])->name('matches.index');

    Route::get('/meus-palpites', [PredictionController::class, 'index'])->name('predictions.index');

    Route::post('/campeao', [ChampionPickController::class, 'store'])->name('champion.store');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');

    // Grupos de bolão
    Route::get('/bolao', [BolaoGroupController::class, 'index'])->name('bolao.index');
    Route::get('/bolao/criar', [BolaoGroupController::class, 'create'])->name('bolao.create');
    Route::post('/bolao', [BolaoGroupController::class, 'store'])->name('bolao.store');
    Route::get('/bolao/entrar', [BolaoGroupController::class, 'join'])->name('bolao.join');
    Route::get('/bolao/buscar', [BolaoGroupController::class, 'search'])->name('bolao.search')->middleware('throttle:20,1');
    Route::post('/bolao/entrar', [BolaoGroupController::class, 'enter'])->name('bolao.enter');
    Route::get('/bolao/{bolaoGroup}', [BolaoGroupController::class, 'show'])->name('bolao.show');
    Route::get('/bolao/{bolaoGroup}/partidas', [BolaoGroupController::class, 'matches'])->name('bolao.matches');
    Route::get('/bolao/{bolaoGroup}/acompanhar', [BolaoGroupController::class, 'watch'])->name('bolao.watch');
    Route::post('/bolao/{bolaoGroup}/partidas/{match}', [BolaoGroupController::class, 'storePrediction'])->name('bolao.predict');
    Route::post('/bolao/{bolaoGroup}/palpites/lote', [BolaoGroupController::class, 'storeBatchPredictions'])->name('bolao.predict.batch');
    Route::delete('/bolao/{bolaoGroup}/sair', [BolaoGroupController::class, 'leave'])->name('bolao.leave');
    Route::delete('/bolao/{bolaoGroup}', [BolaoGroupController::class, 'destroy'])->name('bolao.destroy');

    // Admin
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/resultados', [AdminController::class, 'results'])->name('admin.results');
        Route::patch('/resultados/{match}', [AdminController::class, 'updateResult'])->name('admin.results.update');
        Route::delete('/resultados/{match}', [AdminController::class, 'clearResult'])->name('admin.results.clear');

        Route::get('/eliminatorias/criar', [AdminController::class, 'createKnockout'])->name('admin.knockout.create');
        Route::post('/eliminatorias', [AdminController::class, 'storeKnockout'])->name('admin.knockout.store');

        Route::get('/usuarios', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/usuarios/stats', [AdminController::class, 'userStats'])->name('admin.users.stats');
    });
});
