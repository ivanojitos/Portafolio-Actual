<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    public function logout(): void
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirectRoute('home', navigate: true);
    }
};

?>

<header class="border-b border-white/10 bg-slate-950/90">
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-5 lg:px-8">
        <div>
            <a href="{{ route('admin.dashboard') }}" wire:navigate
                class="font-bold text-white transition hover:text-cyan-300">
                Administración
            </a>

            <p class="mt-1 text-xs text-slate-500">
                {{ auth()->user()->email }}
            </p>
        </div>

        <div class="flex items-center gap-5">

            <a href="{{ route('admin.messages.index') }}" wire:navigate
                class="text-sm text-slate-300 transition hover:text-cyan-300">
                Mensajes
            </a>

            <a href="{{ route('admin.projects.index') }}" wire:navigate
                class="text-sm text-slate-300 transition hover:text-cyan-300">
                Proyectos
            </a>

            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
                class="text-sm text-slate-300 transition hover:text-cyan-300">
                Ver portafolio ↗
            </a>

            <button type="button" wire:click="logout" wire:loading.attr="disabled"
                class="rounded-lg border border-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:border-red-300/50 hover:text-red-300 disabled:opacity-50">
                Cerrar sesión
            </button>
        </div>
    </nav>
</header>
