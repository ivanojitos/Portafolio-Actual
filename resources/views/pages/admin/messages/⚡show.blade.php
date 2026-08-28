<?php

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::app')]
#[Title('Ver mensaje')]
class extends Component
{
    public ContactMessage $contactMessage;

    public function mount(
        ContactMessage $contactMessage
    ): void {
        Gate::authorize('view', $contactMessage);

        $this->contactMessage = $contactMessage;

        if (
            $contactMessage->status
            === ContactMessageStatus::Pending
        ) {
            Gate::authorize('update', $contactMessage);

            $contactMessage->markAsRead();

            $this->contactMessage->refresh();
        }
    }

    public function markAsReplied(): void
    {
        Gate::authorize(
            'update',
            $this->contactMessage
        );

        $this->contactMessage->markAsReplied();
        $this->contactMessage->refresh();

        session()->flash(
            'success',
            'El mensaje fue marcado como respondido.'
        );
    }

    public function markAsSpam(): void
    {
        Gate::authorize(
            'update',
            $this->contactMessage
        );

        $this->contactMessage->markAsSpam();
        $this->contactMessage->refresh();

        session()->flash(
            'success',
            'El mensaje fue marcado como spam.'
        );
    }
};

?>

<div class="min-h-screen">
    <livewire:admin-navigation />

    <main class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <a
            href="{{ route('admin.messages.index') }}"
            wire:navigate
            class="text-sm text-slate-400 transition hover:text-cyan-300"
        >
            ← Volver a mensajes
        </a>

        @if (session('success'))
            <div
                class="mt-8 rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm text-emerald-200"
                role="status"
            >
                {{ session('success') }}
            </div>
        @endif

        <article class="mt-8 overflow-hidden rounded-2xl border border-white/10 bg-slate-900/60">
            <header class="border-b border-white/10 p-6 sm:p-8">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-start">
                    <div>
                        <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-cyan-300">
                            Mensaje de contacto
                        </p>

                        <h1 class="mt-4 text-3xl font-bold text-white">
                            {{ $contactMessage->subject }}
                        </h1>
                    </div>

                    @php
                        $statusColor = match ($contactMessage->status) {
                            ContactMessageStatus::Pending => 'bg-amber-400/10 text-amber-300',
                            ContactMessageStatus::Read => 'bg-cyan-400/10 text-cyan-300',
                            ContactMessageStatus::Replied => 'bg-emerald-400/10 text-emerald-300',
                            ContactMessageStatus::Spam => 'bg-red-400/10 text-red-300',
                        };
                    @endphp

                    <span class="self-start rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                        {{ $contactMessage->status->label() }}
                    </span>
                </div>

                <dl class="mt-8 grid gap-5 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-slate-500">Nombre</dt>
                        <dd class="mt-1 font-medium text-white">
                            {{ $contactMessage->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Correo</dt>
                        <dd class="mt-1">
                            <a
                                href="mailto:{{ $contactMessage->email }}"
                                class="font-medium text-cyan-300 hover:text-cyan-200"
                            >
                                {{ $contactMessage->email }}
                            </a>
                        </dd>
                    </div>

                    @if ($contactMessage->company)
                        <div>
                            <dt class="text-slate-500">Empresa</dt>
                            <dd class="mt-1 font-medium text-white">
                                {{ $contactMessage->company }}
                            </dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-slate-500">Recibido</dt>
                        <dd class="mt-1 text-slate-300">
                            <time datetime="{{ $contactMessage->created_at->toIso8601String() }}">
                                {{ $contactMessage->created_at->translatedFormat('d M Y, H:i') }}
                            </time>
                        </dd>
                    </div>
                </dl>
            </header>

            <div class="p-6 sm:p-8">
                <p class="whitespace-pre-wrap break-words leading-8 text-slate-200">{{ $contactMessage->message }}</p>
            </div>

            <footer class="flex flex-wrap gap-4 border-t border-white/10 p-6 sm:p-8">
                <a
                    href="mailto:{{ $contactMessage->email }}?subject={{ rawurlencode('Re: '.$contactMessage->subject) }}"
                    class="rounded-xl bg-cyan-300 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200"
                >
                    Responder por correo
                </a>

                @if ($contactMessage->status !== ContactMessageStatus::Replied)
                    <button
                        type="button"
                        wire:click="markAsReplied"
                        class="rounded-xl border border-emerald-300/30 px-5 py-3 font-semibold text-emerald-300 transition hover:bg-emerald-300/10"
                    >
                        Marcar respondido
                    </button>
                @endif

                @if ($contactMessage->status !== ContactMessageStatus::Spam)
                    <button
                        type="button"
                        wire:click="markAsSpam"
                        wire:confirm="¿Marcar este mensaje como spam?"
                        class="rounded-xl border border-red-300/30 px-5 py-3 font-semibold text-red-300 transition hover:bg-red-300/10"
                    >
                        Marcar como spam
                    </button>
                @endif
            </footer>
        </article>
    </main>
</div>
