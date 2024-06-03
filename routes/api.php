<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\TeapotController;
use App\Http\Controllers\Api\PaymentController;

// API Routes

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/destroy', [ProfileController::class, 'destroy']);
    Route::post('/profile/store', [ProfileController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', ['Api\UserController@show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

// User routes
Route::middleware(['auth:sanctum', 'user-role:user'])->group(function() {
    Route::get("/home", [HomeController::class, 'userHome']);
    Route::get("/home/fav", [HomeController::class, 'userFav']);
    Route::get("/home/lib", [HomeController::class, 'userLib']);
    Route::get("/home/download", [HomeController::class, 'userDownload']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'user-role:admin'])->group(function() {
    Route::get("/admin/home", [HomeController::class, 'adminHome']);
    Route::get("/admin/profile", [HomeController::class, 'adminProfile']);
    Route::get("/admin/manage", [HomeController::class, 'adminManage']);
    Route::post("/admin/manage", [UserController::class, 'store']);
    Route::get("/admin/manage/add", [UserController::class, 'create']);
    Route::put('/user/update/{id}', [UserController::class, 'update']);
    Route::delete('/user/delete/{id}', [UserController::class, 'destroy']);
    Route::get("/admin/book/add", [HomeController::class, 'addBook']);
    Route::get("/admin/book", [BookController::class, 'index']);
    Route::put("/admin/book/{book}", [BookController::class, 'update']);
    Route::delete("/admin/book/{book}", [BookController::class, 'destroy']);
});

// Crew routes
Route::middleware(['auth:sanctum', 'user-role:crew'])->group(function() {
    Route::get("/crew/home", [HomeController::class, 'crewHome']);
    Route::get("/crew/book/add", [HomeController::class, 'addBookCrew']);
    Route::get("/crew/profile", [HomeController::class, 'crewProfile']);
    Route::get("/add/crew", [HomeController::class, 'crewAdd']);
    Route::get("/list/crew", [HomeController::class, 'crewList']);
    Route::get("/data/crew", [HomeController::class, 'crewData']);
    Route::get("/crew/book", [BookController::class, 'indexCrew']);
    Route::put("/crew/book/{book}", [BookController::class, 'updateCrew']);
});

// Borrow routes
Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/borrow', [BorrowingController::class, 'store']);
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/approval', [BorrowingController::class, 'approval']);
    Route::post('/borrow/approve/{id}', [BorrowingController::class, 'approve']);
    Route::post('/borrow/reject/{id}', [BorrowingController::class, 'reject']);
    Route::post('/borrow/return/{id}', [BorrowingController::class, 'return']);
    Route::get('/crew/list', [BorrowingController::class, 'showBorrowings']);
    Route::get('/crew/data', [BorrowingController::class, 'showReturnedBooks']);
});

// Teapot route
Route::get('/teapot', [TeapotController::class, 'show']);

// Book Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{id}', ['Api\BookController@show']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
});

// Payment routes
Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments/{payment}/pay', [PaymentController::class, 'pay']);
});
