<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogsController;
use App\Http\Middleware\AdminMiddleware;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [BlogsController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{id}/detail', [BlogsController::class, 'detail'])->name('blogs.detail');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //blogs related
    Route::get('/blogs/mine', [BlogsController::class, 'myBlogs'])->name('blogs.my-blogs');
    Route::get('/blogs/{id}/show', [BlogsController::class, 'show'])->name('blogs.show');
    Route::get('/blogs/create', [BlogsController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogsController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{id}/edit', [BlogsController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{id}', [BlogsController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{id}', [BlogsController::class, 'destroy'])->name('blogs.destroy');
});

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/blogs-all', [BlogsController::class, 'allBlogs'])->name('blogs.all-blogs');
    //Route::get('/blogs/{id}/admin/show', [BlogsController::class, 'show'])->name('blogs.show.admin');
    Route::put('/blogs/{id}/change/{status}', [BlogsController::class, 'changeStatus'])->name('blogs.change-status');
});

require __DIR__.'/auth.php';
