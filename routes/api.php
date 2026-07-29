<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/menus', [PublicController::class, 'menus']);
Route::get('/public/pages/{slug}', [PublicController::class, 'page']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Pages
    Route::get('/pages', [PageController::class, 'index'])->middleware('permission:pages.list');
    Route::post('/pages', [PageController::class, 'store'])->middleware('permission:pages.create');
    Route::get('/pages/{page}', [PageController::class, 'show'])->middleware('permission:pages.list');
    Route::put('/pages/{page}', [PageController::class, 'update'])->middleware('permission:pages.edit');
    Route::post('/pages/{page}', [PageController::class, 'update'])->middleware('permission:pages.edit'); // for file uploads
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->middleware('permission:pages.delete');
    Route::post('/pages/{id}/restore', [PageController::class, 'restore'])->middleware('permission:pages.restore');

    // Menus
    Route::get('/menus', [MenuController::class, 'index'])->middleware('permission:menus.list');
    Route::post('/menus', [MenuController::class, 'store'])->middleware('permission:menus.create');
    Route::put('/menus/reorder', [MenuController::class, 'reorder'])->middleware('permission:menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->middleware('permission:menus.edit');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->middleware('permission:menus.delete');

    // Users
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:users.list');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:users.create');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:users.list');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:users.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete');

    // Roles & Permissions
    Route::get('/permissions', [RoleController::class, 'permissions'])->middleware('permission:roles.list');
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:roles.list');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:roles.create');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.edit');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete');
});
