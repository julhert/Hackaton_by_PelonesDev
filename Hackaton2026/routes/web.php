<?php

use App\Livewire\CrearTarea;
use App\Livewire\GestorNotas;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 2. Rutas totalmente PÚBLICAS (las sacamos del middleware)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/crearTarea', CrearTarea::class)->name('crearTarea');
Route::get('/notas', GestorNotas::class)->name('crearNota');

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
//     Route::get('/crearTarea', CrearTarea::class)->name('crearTarea');
// });
