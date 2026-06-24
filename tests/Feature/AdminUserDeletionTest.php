<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_accounts_do_not_render_delete_action(): void
    {
        DB::statement('PRAGMA ignore_check_constraints = ON');

        User::factory()->create([
            'role' => 'admin',
            'name' => 'Super Admin',
            'password' => Hash::make('Password1!'),
        ]);

        User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('Password1!'),
        ]);

        $this->actingAs(User::where('name', '!=', 'Super Admin')->first())
            ->get(route('admin.users'))
            ->assertOk()
            ->assertDontSee('btn-outline-danger')
            ->assertDontSee('method="DELETE"');
    }

    public function test_admin_accounts_cannot_be_deleted_through_the_route(): void
    {
        DB::statement('PRAGMA ignore_check_constraints = ON');

        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('Password1!'),
        ]);

        $viewer = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('Password1!'),
        ]);

        $this->actingAs($viewer)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
        ]);
    }
}
