<?php

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

use App\Http\Controllers\Controller;

Route::get('/halaman', [Controller::class, 'indexHalaman'])->name('halaman1');
        Route::post('/halaman', [Controller::class, 'prosesKata'])->name('halaman1.prosesKata');
        Route::get('/halaman/kata/{kata}',  [Controller::class, 'prosesKataFromLink'])->name('halaman1.kata');
        Route::get('/listtabelkata',[Controller::class, 'prosesTabelKata'])->name('halaman1.prosesTabelKata');

