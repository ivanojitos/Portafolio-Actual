<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')
    ->name('home');

Route::livewire(
    '/proyectos/{project:slug}',
    'pages::projects.show'
)->name('projects.show');
