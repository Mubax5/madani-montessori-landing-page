<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/tentang', fn (PublicPageController $controller) => $controller->show('tentang'))->name('tentang');
Route::get('/program-sekolah', fn (PublicPageController $controller) => $controller->show('program-sekolah'))->name('program-sekolah');
Route::get('/program-unggulan', fn (PublicPageController $controller) => $controller->show('program-unggulan'))->name('program-unggulan');
Route::get('/bimbel', fn (PublicPageController $controller) => $controller->show('bimbel'))->name('bimbel');
Route::get('/training-parenting', fn (PublicPageController $controller) => $controller->show('training-parenting'))->name('training-parenting');
Route::get('/galeri', fn (PublicPageController $controller) => $controller->show('galeri'))->name('galeri');
Route::get('/kontak', fn (PublicPageController $controller) => $controller->show('kontak'))->name('kontak');

Route::post('/pendaftaran', [LeadController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('leads.store');
