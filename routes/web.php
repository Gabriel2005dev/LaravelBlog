<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedPostController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | FEED
    |--------------------------------------------------------------------------
    */

    Route::get('/feed', [PostController::class, 'index'])
        ->name('feed');


    /*
    |--------------------------------------------------------------------------
    | POSTS
    |--------------------------------------------------------------------------
    */

    // Criar post
    Route::post('/posts', [PostController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('posts.store');


    // Atualizar post
    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');


    // Deletar post
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');



    /*
    |--------------------------------------------------------------------------
    | POSTS LIKE
    |--------------------------------------------------------------------------
    */

    Route::post('/posts/{post}/like', PostLikeController::class)
        ->middleware('throttle:30,1')
        ->name('posts.like.toggle');



    /*
    |--------------------------------------------------------------------------
    | POSTS SALVOS
    |--------------------------------------------------------------------------
    */

    Route::post('/posts/{post}/save', SavedPostController::class)
        ->middleware('throttle:30,1')
        ->name('posts.save.toggle');



    /*
    |--------------------------------------------------------------------------
    | POSTS CURTIDOS
    |--------------------------------------------------------------------------
    */

    Route::get('/posts/liked', [PostController::class, 'liked'])
        ->name('posts.liked.index');



    /*
    |--------------------------------------------------------------------------
    | POSTS SALVOS LISTAGEM
    |--------------------------------------------------------------------------
    */

    Route::get('/posts/saved', [PostController::class, 'saved'])
        ->name('posts.saved.index');



    /*
    |--------------------------------------------------------------------------
    | COMMENTS
    |--------------------------------------------------------------------------
    */

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->middleware('throttle:20,1')
        ->name('posts.comments.store');


    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

});



/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');


    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->middleware('throttle:6,1')
        ->name('profile.avatar');

});


require __DIR__.'/auth.php';