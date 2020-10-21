<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;



Route::apiResource('/user', UserController::class)->middleware('auth:api');
Route::post('/auth', [UserController::class, 'auth']);
Route::post('/logout', [UserController::class, 'logout']);

Route::apiResource('/author', AuthorController::class);

Route::get('/posts/author/{id}', [AuthorController::class, 'authorPosts']);

Route::apiResource('/post', PostController::class);

Route::apiResource('/categories', CategoryController::class);
Route::get('/categories/{id}/posts', [CategoryController::class, 'GetPostByCateory']);
Route::get('/post/categories/{id}', [CategoryController::class, 'GetPostByCateorySort']);

Route::apiResource('/comment', CommentController::class);

