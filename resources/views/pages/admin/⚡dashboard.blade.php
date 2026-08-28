<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::app')]
#[Title('Panel administrativo')]
class extends Component
{
    //
};

?>

<main class="min-h-screen px-6 py-16">
    <div class="mx-auto max-w-7xl">
        <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
            Área privada
        </p>

        <h1 class="mt-4 text-4xl font-bold text-white">
            Panel administrativo
        </h1>

        <p class="mt-4 text-slate-300">
            Sesión iniciada como {{ auth()->user()->name }}.
        </p>
    </div>
</main>
