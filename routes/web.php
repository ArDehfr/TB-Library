<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile/admin', [ProfileController::class, 'adminEdit'])->name('admin.profile');
    Route::patch('/profile/admin', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/admin', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

//user route
Route::middleware(['auth','user-role:user'])->group(function()
{
    Route::get("/home",[HomeController::class, 'userHome'])->name('home');
});
Route::middleware(['auth','user-role:user'])->group(function()
{
    Route::get("/home/fav",[HomeController::class, 'userFav'])->name('home.fav');
});
Route::middleware(['auth','user-role:user'])->group(function()
{
    Route::get("/home/lib",[HomeController::class, 'userLib'])->name('home.lib');
});
Route::middleware(['auth','user-role:user'])->group(function()
{
    Route::get("/home/download",[HomeController::class, 'userDownload'])->name('home.download');
});

//admin route
Route::middleware(['auth','user-role:admin'])->group(function()
{
    Route::get("/admin/home",[HomeController::class, 'adminHome'])->name('home.admin');
});

Route::middleware(['auth','user-role:admin'])->group(function()
{
    Route::get("/admin/profile",[HomeController::class, 'adminProfile'])->name('profile.admin');
});

Route::middleware(['auth','user-role:admin'])->group(function()
{
    Route::get("/admin/manage", [HomeController::class, 'adminManage'])->name('admin.manage');
    Route::post("/admin/manage", [UserController::class, 'store'])->name('admin.manage.store');
    Route::get("/admin/manage/add", [UserController::class, 'create'])->name('admin.manage.add');
    Route::put('/user/update/{id}', [UserController::class, 'update'])->name('update_user');
    Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('delete_user');
});

Route::middleware(['auth','user-role:admin'])->group(function()
{
    Route::get("/admin/manage/add",[HomeController::class, 'addUser'])->name('admin.add');
    Route::get("/admin/book/add",[HomeController::class, 'addBook'])->name('admin.add-book');
});

Route::middleware(['auth','user-role:admin'])->group(function()
{
    Route::get("/admin/book",[HomeController::class, 'adminBook'])->name('admin.book');
    Route::post("/admin/book",[HomeController::class, 'adminBook'])->name('admin.book');
    Route::put("/admin/book",[HomeController::class, 'adminBook'])->name('admin.book');
});

//crew route
Route::middleware(['auth','user-role:crew'])->group(function()
{
    Route::get("/crew/home",[HomeController::class, 'crewHome'])->name('home.crew');
});

Route::middleware(['auth','user-role:crew'])->group(function()
{
    Route::get("/crew/profile",[HomeController::class, 'crewProfile'])->name('profile.crew');
});

Route::middleware(['auth','user-role:crew'])->group(function()
{
    Route::get("/add/crew",[HomeController::class, 'crewAdd'])->name('add.crew');
});

Route::middleware(['auth','user-role:crew'])->group(function()
{
    Route::get("/list/crew",[HomeController::class, 'crewList'])->name('list.crew');
});

Route::middleware(['auth','user-role:crew'])->group(function()
{
    Route::get("/data/crew",[HomeController::class, 'crewData'])->name('data.crew');
});

// Book Routes
Route::resource('books', BookController::class)->except(['index', 'destroy', 'update']);
Route::get('/admin/book', [BookController::class, 'index'])->name('admin.book');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
Route::put('/books/{book}', [BookController::class, 'update'])->name('admin.book.update');

// Borrow Routes

Route::post('/borrow', [BorrowingController::class, 'store'])->name('borrow.book');
Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.list');
Route::get('/approval', [BorrowingController::class, 'approval'])->name('home.download');
Route::post('/borrow/approve/{id}', [BorrowingController::class, 'approve'])->name('borrow.approve');
Route::post('/borrow/reject/{id}', [BorrowingController::class, 'reject'])->name('borrow.reject');
Route::post('/borrow/return/{id}', [BorrowingController::class, 'return'])->name('borrow.return');
Route::get('/crew/list', [BorrowingController::class, 'showBorrowings'])->name('list.crew');
Route::get('/crew/data', [BorrowingController::class, 'showReturnedBooks'])->name('data.crew');
