<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('KarsaERP');
        $response->assertSee('Data Pemilik');
        $response->assertSee('Keamanan');
        $response->assertSee('Informasi Usaha');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081299887766',
            'password' => 'password',
            'password_confirmation' => 'password',
            'business_name' => 'Toko Baru Makmur',
            'business_type' => 'retail',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('businesses', [
            'name' => 'Toko Baru Makmur',
            'business_type' => 'retail',
        ]);
    }

    public function test_registration_fails_when_required_fields_are_missing(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'business_name', 'business_type']);
        $this->assertGuest();
    }
}
