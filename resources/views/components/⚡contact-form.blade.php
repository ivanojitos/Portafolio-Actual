<?php

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $company = '';
    public string $subject = '';
    public string $message = '';

    public string $faxNumber = '';
    public int $formStartedAt;
    public bool $success = false;

    public function mount(): void
    {
        $this->formStartedAt = now()->timestamp;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'company' => ['nullable', 'string', 'max:120'],
            'subject' => ['required', 'string', 'min:5', 'max:160'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Escribe tu nombre.',
            'name.min' => 'El nombre debe contener al menos dos caracteres.',
            'name.max' => 'El nombre no puede superar 100 caracteres.',

            'email.required' => 'Escribe tu correo.',
            'email.email' => 'Escribe un correo válido.',
            'email.max' => 'El correo es demasiado largo.',

            'company.max' => 'La empresa no puede superar 120 caracteres.',

            'subject.required' => 'Escribe el asunto.',
            'subject.min' => 'El asunto debe contener al menos cinco caracteres.',
            'subject.max' => 'El asunto no puede superar 160 caracteres.',

            'message.required' => 'Escribe tu mensaje.',
            'message.min' => 'El mensaje debe contener al menos 20 caracteres.',
            'message.max' => 'El mensaje no puede superar 5000 caracteres.',
        ];
    }

    public function send(): void
    {
        $this->success = false;

        if (
            $this->faxNumber !== ''
            || now()->timestamp - $this->formStartedAt < 2
        ) {
            $this->pretendSuccess();

            return;
        }

        $validated = $this->validate();

        $ipHash = hash_hmac(
            'sha256',
            (string) request()->ip(),
            (string) config('app.key')
        );

        $rateLimitKey = 'contact-message:'.$ipHash;

        $stored = RateLimiter::attempt(
            $rateLimitKey,
            3,
            function () use ($validated, $ipHash): bool {
                ContactMessage::query()->create([
                    'name' => trim($validated['name']),
                    'email' => Str::lower(trim($validated['email'])),
                    'company' => filled($validated['company'])
                        ? trim($validated['company'])
                        : null,
                    'subject' => trim($validated['subject']),
                    'message' => trim($validated['message']),
                    'status' => ContactMessageStatus::Pending,
                    'ip_hash' => $ipHash,
                    'user_agent' => Str::limit(
                        (string) request()->userAgent(),
                        500,
                        ''
                    ),
                ]);

                return true;
            },
            600
        );

        if (! $stored) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            $this->addError(
                'rateLimit',
                "Has enviado varios mensajes. Intenta nuevamente en {$seconds} segundos."
            );

            return;
        }

        $this->pretendSuccess();
    }

    private function pretendSuccess(): void
    {
        $this->reset([
            'name',
            'email',
            'company',
            'subject',
            'message',
            'faxNumber',
        ]);

        $this->resetValidation();

        $this->formStartedAt = now()->timestamp;
        $this->success = true;
    }
};

?>

<div class="mx-auto mt-12 max-w-3xl text-left">
    @if ($success)
        <div
            class="mb-6 rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm text-emerald-200"
            role="status"
        >
            Gracias. Tu mensaje fue recibido correctamente.
        </div>
    @endif

    @error('rateLimit')
        <div
            class="mb-6 rounded-xl border border-amber-400/20 bg-amber-400/10 p-4 text-sm text-amber-200"
            role="alert"
        >
            {{ $message }}
        </div>
    @enderror

    <form
        wire:submit="send"
        class="grid gap-6 rounded-2xl border border-white/10 bg-slate-950/60 p-6 sm:p-8"
    >
        <div
            class="absolute -left-[10000px] h-px w-px overflow-hidden"
            aria-hidden="true"
        >
            <label for="fax-number">
                No completar este campo
            </label>

            <input
                id="fax-number"
                type="text"
                wire:model="faxNumber"
                tabindex="-1"
                autocomplete="off"
            >
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="contact-name" class="text-sm font-medium text-slate-200">
                    Nombre
                </label>

                <input
                    id="contact-name"
                    type="text"
                    wire:model.blur="name"
                    autocomplete="name"
                    class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-email" class="text-sm font-medium text-slate-200">
                    Correo
                </label>

                <input
                    id="contact-email"
                    type="email"
                    wire:model.blur="email"
                    autocomplete="email"
                    class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300"
                >

                @error('email')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="contact-company" class="text-sm font-medium text-slate-200">
                Empresa <span class="text-slate-500">(opcional)</span>
            </label>

            <input
                id="contact-company"
                type="text"
                wire:model.blur="company"
                autocomplete="organization"
                class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300"
            >

            @error('company')
                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact-subject" class="text-sm font-medium text-slate-200">
                Asunto
            </label>

            <input
                id="contact-subject"
                type="text"
                wire:model.blur="subject"
                class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300"
            >

            @error('subject')
                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact-message" class="text-sm font-medium text-slate-200">
                Mensaje
            </label>

            <textarea
                id="contact-message"
                wire:model.blur="message"
                rows="6"
                class="mt-2 w-full resize-y rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300"
            ></textarea>

            <div class="mt-2 flex justify-between gap-4">
                @error('message')
                    <p class="text-sm text-red-300">{{ $message }}</p>
                @enderror

                <span class="ml-auto text-xs text-slate-500">
                    Máximo 5000 caracteres
                </span>
            </div>
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="send"
            class="inline-flex items-center justify-center rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200 disabled:cursor-wait disabled:opacity-60"
        >
            <span wire:loading.remove wire:target="send">
                Enviar mensaje
            </span>

            <span wire:loading wire:target="send">
                Enviando...
            </span>
        </button>
    </form>
</div>
