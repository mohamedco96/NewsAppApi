<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PostController;



Route::apiResource('/author', AuthorController::class);
Route::get('/posts/author/{id}', [AuthorController::class, 'authorPosts']);

Route::apiResource('/post', PostController::class);


