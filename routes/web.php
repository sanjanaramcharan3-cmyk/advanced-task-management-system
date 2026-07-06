<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// ✅ Task board (main page after login)
Route::get('/tasks', function () {
    return view('tasks.index');
})->middleware('auth');

// ✅ Redirect dashboard → tasks
Route::get('/dashboard', function () {
    return redirect('/tasks');
})->name('dashboard');

// Auth routes
require __DIR__.'/auth.php';