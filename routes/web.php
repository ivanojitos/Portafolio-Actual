<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
    ->name('home');

Route::livewire(
    '/proyectos/{project:slug}',
    'pages::projects.show'
)->name('projects.show');

Route::livewire(
    '/admin/iniciar-sesion',
    'pages::admin.login'
)
    ->middleware('guest')
    ->name('login');

Route::livewire(
    '/admin',
    'pages::admin.dashboard'
)
    ->middleware([
        'auth',
        'admin',
    ])
    ->name('admin.dashboard');
