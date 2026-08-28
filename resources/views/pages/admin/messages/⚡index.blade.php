<?php

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] #[Title('Mensajes de contacto')] class extends Component {
    use WithPagination;

    #[Url(as: 'buscar', except: '')]
    public string $search = '';

    #[Url(as: 'estado', except: '')]
    public string $status = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', ContactMessage::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function messages()
    {
        return ContactMessage::query()
            ->when(
                filled($this->search),
                fn($query) => $query->where(function ($query): void {
                    $search = '%' . $this->search . '%';

                    $query->where('name', 'like', $search)->orWhere('email', 'like', $search)->orWhere('company', 'like', $search)->orWhere('subject', 'like', $search);
                }),
            )
            ->when(filled($this->status), fn($query) => $query->where('status', $this->status))
            ->latest()
            ->paginate(15);
    }

    #[Computed]
    public function statuses(): array
    {
        return ContactMessageStatus::cases();
    }
};

?>

<div class="min-h-screen">
    <livewire:admin-navigation />

    <main class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div>
            <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                Comunicación
            </p>

            <h1 class="mt-4 text-4xl font-bold text-white">
                Mensajes
            </h1>

            <p class="mt-3 text-slate-400">
                Consulta los mensajes enviados desde el formulario del portafolio.
            </p>
        </div>

        <div class="mt-10 grid gap-4 md:grid-cols-[1fr_14rem]">
            <div>
                <label for="message-search" class="sr-only">
                    Buscar mensajes
                </label>

                <input id="message-search" type="search" wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre, correo, empresa o asunto..."
                    class="w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none placeholder:text-slate-600 focus:border-cyan-300">
            </div>

            <div>
                <label for="message-status" class="sr-only">
                    Filtrar por estado
                </label>

                <select id="message-status" wire:model.live="status"
                    class="w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none focus:border-cyan-300">
                    <option value="">Todos los estados</option>

                    @foreach ($this->statuses as $statusOption)
                        <option value="{{ $statusOption->value }}">
                            {{ $statusOption->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-8 overflow-hidden rounded-2xl border border-white/10">
            @if ($this->messages->isEmpty())
                <div class="p-10 text-center text-slate-400">
                    No se encontraron mensajes.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-slate-900/80">
                            <tr class="text-left text-xs uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-4">Remitente</th>
                                <th class="px-6 py-4">Asunto</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4">Fecha</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/10 bg-slate-950/50">
                            @foreach ($this->messages as $contactMessage)
                                <tr wire:key="message-{{ $contactMessage->id }}">
                                    <td class="px-6 py-5">
                                        <p class="font-semibold text-white">
                                            {{ $contactMessage->name }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $contactMessage->email }}
                                        </p>

                                        @if ($contactMessage->company)
                                            <p class="mt-1 text-xs text-slate-600">
                                                {{ $contactMessage->company }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5">
                                        <a href="{{ route('admin.messages.show', [
                                            'contactMessage' => $contactMessage,
                                        ]) }}"
                                            wire:navigate
                                            class="block max-w-md font-medium text-slate-200 transition hover:text-cyan-300">
                                            {{ $contactMessage->subject }}
                                        </a>

                                        <p class="mt-2 max-w-md truncate text-sm text-slate-500">
                                            {{ $contactMessage->message }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        @php
                                            $statusColor = match ($contactMessage->status) {
                                                ContactMessageStatus::Pending => 'bg-amber-400/10 text-amber-300',
                                                ContactMessageStatus::Read => 'bg-cyan-400/10 text-cyan-300',
                                                ContactMessageStatus::Replied => 'bg-emerald-400/10 text-emerald-300',
                                                ContactMessageStatus::Spam => 'bg-red-400/10 text-red-300',
                                            };
                                        @endphp

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                                            {{ $contactMessage->status->label() }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-400">
                                        <time datetime="{{ $contactMessage->created_at->toIso8601String() }}">
                                            {{ $contactMessage->created_at->translatedFormat('d M Y, H:i') }}
                                        </time>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-white/10 bg-slate-900/40 px-6 py-4">
                    {{ $this->messages->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
