<?php

namespace Tests\Feature\Livewire;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private string $rateLimitKey;

    protected function setUp(): void
    {
        parent::setUp();

        $ipHash = hash_hmac(
            'sha256',
            '127.0.0.1',
            (string) config('app.key')
        );

        $this->rateLimitKey = 'contact-message:'.$ipHash;

        RateLimiter::clear($this->rateLimitKey);
    }

    protected function tearDown(): void
    {
        RateLimiter::clear($this->rateLimitKey);

        parent::tearDown();
    }

    public function test_valid_contact_message_can_be_stored(): void
    {
        Livewire::test('contact-form')
            ->set('formStartedAt', now()->subSeconds(5)->timestamp)
            ->set('name', 'María López')
            ->set('email', 'MARIA@EXAMPLE.COM')
            ->set('company', 'Empresa de prueba')
            ->set('subject', 'Desarrollo de una aplicación')
            ->set(
                'message',
                'Me gustaría conversar sobre el desarrollo de una aplicación empresarial.'
            )
            ->call('send')
            ->assertHasNoErrors()
            ->assertSet('success', true);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'María López',
            'email' => 'maria@example.com',
            'company' => 'Empresa de prueba',
            'subject' => 'Desarrollo de una aplicación',
            'status' => 'pending',
        ]);
    }

    public function test_invalid_contact_message_is_rejected(): void
    {
        Livewire::test('contact-form')
            ->set('formStartedAt', now()->subSeconds(5)->timestamp)
            ->set('name', '')
            ->set('email', 'correo-invalido')
            ->set('subject', 'Hola')
            ->set('message', 'Mensaje corto')
            ->call('send')
            ->assertHasErrors([
                'name',
                'email',
                'subject',
                'message',
            ]);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_honeypot_submission_is_not_stored(): void
    {
        Livewire::test('contact-form')
            ->set('formStartedAt', now()->subSeconds(5)->timestamp)
            ->set('name', 'Bot automático')
            ->set('email', 'bot@example.com')
            ->set('subject', 'Mensaje automatizado')
            ->set(
                'message',
                'Este mensaje tiene suficiente longitud para superar la validación.'
            )
            ->set('faxNumber', '123456')
            ->call('send')
            ->assertSet('success', true);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_is_rate_limited(): void
    {
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            Livewire::test('contact-form')
                ->set('formStartedAt', now()->subSeconds(5)->timestamp)
                ->set('name', "Usuario {$attempt}")
                ->set('email', "usuario{$attempt}@example.com")
                ->set('subject', 'Solicitud de información')
                ->set(
                    'message',
                    'Quiero solicitar información sobre servicios de desarrollo de software.'
                )
                ->call('send')
                ->assertHasNoErrors();
        }

        Livewire::test('contact-form')
            ->set('formStartedAt', now()->subSeconds(5)->timestamp)
            ->set('name', 'Cuarto usuario')
            ->set('email', 'cuarto@example.com')
            ->set('subject', 'Solicitud de información')
            ->set(
                'message',
                'Este cuarto mensaje debe ser rechazado por el límite configurado.'
            )
            ->call('send')
            ->assertHasErrors([
                'rateLimit',
            ]);

        $this->assertDatabaseCount('contact_messages', 3);
    }
}
