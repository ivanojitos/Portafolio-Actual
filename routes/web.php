<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
    ->name('home');

Route::livewire(
    '/proyectos/{project:slug}',
    'pages::projects.show'
)->name('projects.show');
