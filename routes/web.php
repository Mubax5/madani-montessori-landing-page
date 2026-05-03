<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/tentang', fn (PublicPageController $controller) => $controller->show('tentang'))->name('tentang');
Route::get('/program-sekolah', fn (PublicPageController $controller) => $controller->show('program-sekolah'))->name('program-sekolah');
Route::get('/program-unggulan', fn (PublicPageController $controller) => $controller->show('program-unggulan'))->name('program-unggulan');
Route::get('/bimbel', fn (PublicPageController $controller) => $controller->show('bimbel'))->name('bimbel');
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{slug}', [AgendaController::class, 'show'])->name('agenda.show');
Route::post('/agenda/{agenda:slug}/registrations', [AgendaController::class, 'storeRegistration'])
    ->middleware('throttle:5,1')
    ->name('agenda.registrations.store');
Route::redirect('/training-parenting', '/agenda', 301)->name('training-parenting');
Route::get('/galeri', fn (PublicPageController $controller) => $controller->show('galeri'))->name('galeri');
Route::get('/kontak', fn (PublicPageController $controller) => $controller->show('kontak'))->name('kontak');

Route::post('/pendaftaran', [LeadController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('leads.store');
