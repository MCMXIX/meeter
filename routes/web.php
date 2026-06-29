<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Account Routes
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');

    // Profile Routes
    Route::prefix('/profile')->group(function() {
        Route::get('/{id?}', [ProfileController::class, 'index'])->name('profile.get');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/visit', [ProfileController::class, 'addVisitCount'])->name('profile.visit');
    });

    // Conversations Routes
    Route::prefix('/conversations')->group(function() {
        Route::get('/{id?}', [ConversationController::class, 'index'])->name('conversation.get');
        Route::get('/messages/{id}', [ConversationController::class, 'getMessages'])->name('conversation.get.messages');
        Route::get('/check/conversation/{user_id}', [ConversationController::class, 'checkConversation'])->name('conversation.check');
        Route::post('/', [ConversationController::class, 'sendMessage'])->name('conversation.send.message');
    });

    Route::prefix('/post')->group(function() {
        Route::get('/{user_id?}', [PostController::class, 'getPost'])->name('post.get');
        Route::post('/', [PostController::class, 'createPost'])->name('post.create');
    });

    // Users Routes
    Route::prefix('/user')->group(function() {
        Route::get('/search', [RegisteredUserController::class, 'search'])->name('user.search');
    });
});

require __DIR__.'/auth.php';
