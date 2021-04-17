<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('index');;
})->name('/');

Route::prefix('dashboard')->group(function () {
    Route::view('index', 'dashboard.index')->name('index');
});

Route::prefix('starter-kit')->group(function () {
    Route::view('layout-light', 'starter-kit.layout-light')->name('layout-light');
    Route::view('layout-dark', 'starter-kit.layout-dark')->name('layout-dark');
    Route::view('layout-box', 'starter-kit.layout-box')->name('layout-box');
    Route::view('layout-rtl', 'starter-kit.layout-rtl')->name('layout-rtl');
    Route::view('compact-layout', 'starter-kit.compact-layout')->name('compact-layout');
    Route::view('vertical-layout', 'starter-kit.vertical-layout')->name('vertical-layout');
    Route::view('dark-rtl-layout', 'starter-kit.dark-rtl-layout')->name('dark-rtl-layout');
    Route::view('vertical-rtl-layout', 'starter-kit.vertical-rtl-layout')->name('vertical-rtl-layout');
    Route::view('compact-rtl-layout', 'starter-kit.compact-rtl-layout')->name('compact-rtl-layout');
    Route::view('dark-box-layout', 'starter-kit.dark-box-layout')->name('dark-box-layout');
    Route::view('vertical-box-layout', 'starter-kit.vertical-box-layout')->name('vertical-box-layout');
    Route::view('compact-dark-layout', 'starter-kit.compact-dark-layout')->name('compact-dark-layout');
    Route::view('hide-on-scroll', 'starter-kit.hide-on-scroll')->name('hide-on-scroll');
    Route::view('footer-light', 'starter-kit.footer-light')->name('footer-light');
    Route::view('footer-dark', 'starter-kit.footer-dark')->name('footer-dark');
    Route::view('footer-fixed', 'starter-kit.footer-fixed')->name('footer-fixed');
});


Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');