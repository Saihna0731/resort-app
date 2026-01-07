<?php

use App\Http\Controllers\ResortController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [ResortController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');