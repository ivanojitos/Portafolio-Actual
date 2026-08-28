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

Route::livewire(
    '/admin/proyectos',
    'pages::admin.projects.index'
)
    ->middleware([
        'auth',
        'admin',
    ])
    ->name('admin.projects.index');

Route::livewire(
    '/admin/proyectos/nuevo',
    'pages::admin.projects.create'
)
    ->middleware([
        'auth',
        'admin',
    ])
    ->name('admin.projects.create');

Route::livewire(
    '/admin/proyectos/{project:slug}/editar',
    'pages::admin.projects.create'
)
    ->middleware([
        'auth',
        'admin',
    ])
    ->name('admin.projects.edit');

Route::livewire(
    '/admin/mensajes',
    'pages::admin.messages.index'
)
    ->middleware([
        'auth',
        'admin',
    ])
    ->name('admin.messages.index');
