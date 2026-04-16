<?php

use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return "Ini adalah dashboard, nanti akan diganti dengan halaman yang sebenarnya";
    })->name('dashboard');
});