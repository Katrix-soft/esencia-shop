<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('mega-admin')->name('mega-admin.')->group(function () {
    Route::get('/', function () {
        return view('mega-admin::dashboard');
    })->name('dashboard');
});
