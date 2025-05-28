<?php

use App\Http\Controllers\ContagemController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/contagem', [ContagemController::class, 'increment'])->name('contagem.increment');
    Route::get('/contagem/estatisticas', [ContagemController::class, 'estatisticas'])->name('contagem.estatisticas');

    Route::get('/table-results', [ContagemController::class, 'tableResults'])->name('table-results');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
