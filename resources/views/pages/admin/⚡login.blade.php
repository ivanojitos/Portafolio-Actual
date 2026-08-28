<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::app')]
#[Title('Administración | Ivan Alvarez Valencia')]
class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:180'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['boolean'],
        ];
    }

    public function login(): void
    {
        $credentials = $this->validate();

        $email = Str::lower(trim($credentials['email']));

        $rateLimitKey = 'admin-login:'.hash(
            'sha256',
            $email.'|'.request()->ip()
        );

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            $this->addError(
                'email',
                "Demasiados intentos. Intenta nuevamente en {$seconds} segundos."
            );

            return;
        }

        $authenticated = Auth::attempt(
            [
                'email' => $email,
                'password' => $credentials['password'],
            ],
            $credentials['remember']
        );

        if (
            ! $authenticated
            || Auth::user()?->is_admin !== true
        ) {
            Auth::logout();

            RateLimiter::hit($rateLimitKey, 60);

            $this->addError(
                'email',
                'Las credenciales proporcionadas no son válidas.'
            );

            $this->reset('password');

            return;
        }

        RateLimiter::clear($rateLimitKey);

        request()->session()->regenerate();

        $this->redirectRoute(
            'admin.dashboard',
            navigate: true
        );
    }
};

?>

<main class="flex min-h-screen items-center justify-center px-6 py-16">
    <div class="w-full max-w-md">
        <a
            href="{{ route('home') }}"
            class="text-sm text-slate-400 transition hover:text-cyan-300"
        >
            ← Volver al portafolio
        </a>

        <div class="mt-8 rounded-2xl border border-white/10 bg-slate-900/70 p-8">
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.25em] text-cyan-300">
                Área privada
            </p>

            <h1 class="mt-4 text-3xl font-bold text-white">
                Administración
            </h1>

            <p class="mt-3 text-sm leading-6 text-slate-400">
                Inicia sesión para administrar el contenido del portafolio.
            </p>

            <form wire:submit="login" class="mt-8 space-y-6">
                <div>
                    <label for="admin-email" class="text-sm font-medium text-slate-200">
                        Correo
                    </label>

                    <input
                        id="admin-email"
                        type="email"
                        wire:model.blur="email"
                        autocomplete="username"
                        autofocus
                        class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="admin-password" class="text-sm font-medium text-slate-200">
                        Contraseña
                    </label>

                    <input
                        id="admin-password"
                        type="password"
                        wire:model="password"
                        autocomplete="current-password"
                        class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300"
                    >

                    @error('password')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input
                        type="checkbox"
                        wire:model="remember"
                        class="rounded border-white/20 bg-slate-950 text-cyan-300 focus:ring-cyan-300"
                    >

                    Mantener sesión iniciada
                </label>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="login"
                    class="w-full rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200 disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="login">
                        Iniciar sesión
                    </span>

                    <span wire:loading wire:target="login">
                        Verificando...
                    </span>
                </button>
            </form>
        </div>
    </div>
</main>
