<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginBackHistoryLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_visiting_login_page_is_logged_out(): void
    {
        $user = User::factory()->create([
            'role' => 'pharmacist',
            'password' => Hash::make('Password1!'),
        ]);

        $this->actingAs($user)
            ->get(route('login'))
            ->assertOk();

        $this->assertGuest();

        $this->get(route('pharmacist.dashboard'))
            ->assertRedirect(route('login'));
    }
}
