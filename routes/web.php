<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('welcome');
// })->middleware('auth');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect()->route('login');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


require __DIR__.'/auth.php';
require __DIR__.'/attendance.php';
require __DIR__.'/db_backup.php';
