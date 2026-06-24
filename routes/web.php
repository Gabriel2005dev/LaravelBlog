<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\SavedPostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/feed', [PostController::class, 'index'])->name('feed');
    Route::get('/posts/liked', [PostController::class, 'liked'])->name('posts.liked.index');
    Route::get('/posts/saved', [PostController::class, 'saved'])->name('posts.saved.index');
    Route::post('/posts/{post}/like', PostLikeController::class)->name('posts.like.toggle');
    Route::post('/posts/{post}/save', SavedPostController::class)->name('posts.save.toggle');
    Route::resource('posts', PostController::class)->except('index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

require __DIR__.'/auth.php';
