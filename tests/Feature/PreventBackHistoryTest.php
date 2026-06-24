<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PreventBackHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_sends_no_cache_headers(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT')
            ->assertHeader('Surrogate-Control', 'no-store');
    }

    public function test_authenticated_pages_sends_no_cache_headers(): void
    {
        $user = User::factory()->create([
            'role' => 'pharmacist',
            'password' => Hash::make('Password1!'),
        ]);

        $this->actingAs($user)
            ->get(route('pharmacist.dashboard'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT')
            ->assertHeader('Surrogate-Control', 'no-store');
    }
}
