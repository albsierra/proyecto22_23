<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('encuentro');
});

Route::get('encuentro', function () {
    return view('encuentro');
});

Route::get('/', function () {
    return view('about');
});

Route::get('thingsICanDo', function () {
    return view('thingsICanDo');
});

Route::get('aFewAccomplishments', function () {
    return view('aFewAccomplishments');
});

Route::get('contact', function () {
    return view('contact');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
