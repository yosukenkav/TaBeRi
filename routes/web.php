<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ChatController;

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
// Route::get('/dish', [DishController::class, 'index'])->name('dish.index');
// Route::post('/dish', [DishController::class, 'chat_2'])->name('dish.chat_2');

// Route::get('/dish', [ChatController::class, 'index'])->name('chat.index');
// Route::post('/dish/create', [ChatController::class, 'chat'])->name('chat.chat');

// Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
// Route::post('/chat', [ChatController::class, 'chat_gpt'])->name('chat.chat_gpt');


Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat', [ChatController::class, 'chat'])->name('chat.chat');

Route::resource('dish', DishController::class);

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
});

require __DIR__.'/auth.php';
