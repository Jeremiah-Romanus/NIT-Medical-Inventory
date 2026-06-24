<?php

namespace Tests\Feature;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_change_password_from_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'pharmacist',
            'password' => Hash::make('OldPassword1!'),
        ]);

        $this->actingAs($user)
            ->put(route('profile.password'), [
                'current_password' => 'OldPassword1!',
                'password' => 'NewPassword1!',
                'password_confirmation' => 'NewPassword1!',
            ])
            ->assertStatus(302)
            ->assertSessionHas('success', 'Password updated successfully.');

        $user->refresh();

        $this->assertTrue(Hash::check('NewPassword1!', $user->password));
    }

    public function test_forgot_password_sends_otp_email_and_stores_code(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'role' => 'pharmacist',
            'email' => 'pharmacist@example.com',
        ]);

        $this->post(route('password.email'), [
            'email' => $user->email,
        ])->assertRedirect(route('password.reset', ['email' => $user->email]));

        Mail::assertSent(PasswordResetOtpMail::class, function (PasswordResetOtpMail $mail) use ($user) {
            return $mail->user->is($user) && preg_match('/^\d{6}$/', $mail->otp) === 1;
        });

        $this->assertNotNull(Cache::get('password-otp:' . strtolower($user->email)));
    }
}
