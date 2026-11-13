<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function as(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_new_accounts_get_the_user_role(): void
    {
        $this->post('/register', [
            'name' => 'New Person',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertSame('user', User::where('email', 'new@example.com')->value('role'));
    }

    public function test_user_sees_only_shop_and_profile(): void
    {
        $user = $this->as('user');

        $this->actingAs($user)->get('/products')->assertOk();
        $this->actingAs($user)->get('/profile')->assertOk()
            ->assertSee('Profile')
            ->assertDontSee(route('dashboard.products.index'))
            ->assertDontSee(route('dashboard.categories.index'))
            ->assertDontSee(route('dashboard.users.index'));

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/dashboard/products')->assertForbidden();
        $this->actingAs($user)->get('/dashboard/categories')->assertForbidden();
        $this->actingAs($user)->get('/dashboard/users')->assertForbidden();
    }

    public function test_admin_sees_everything_except_user_management(): void
    {
        $admin = $this->as('admin');

        $this->actingAs($admin)->get('/dashboard')->assertOk()
            ->assertSee(route('dashboard.products.index'))
            ->assertSee(route('dashboard.categories.index'))
            ->assertDontSee('User Management');
        $this->actingAs($admin)->get('/dashboard/products')->assertOk();
        $this->actingAs($admin)->get('/dashboard/categories')->assertOk();
        $this->actingAs($admin)->get('/products')->assertOk();
        $this->actingAs($admin)->get('/dashboard/users')->assertForbidden();
    }

    public function test_system_admin_sees_every_menu(): void
    {
        $boss = $this->as('system_admin');

        $this->actingAs($boss)->get('/dashboard')->assertOk()
            ->assertSee(route('dashboard.products.index'))
            ->assertSee(route('dashboard.categories.index'))
            ->assertSee(route('dashboard.users.index'))
            ->assertSee('User Management');
        $this->actingAs($boss)->get('/dashboard/products')->assertOk();
        $this->actingAs($boss)->get('/dashboard/categories')->assertOk();
    }

    public function test_user_list_shows_name_email_role_and_can_be_searched(): void
    {
        $boss = $this->as('system_admin');
        User::factory()->create(['name' => 'Adinda Melati', 'email' => 'adinda@example.com', 'role' => 'user']);
        User::factory()->create(['name' => 'Arvan Hariyanto', 'email' => 'arvan@example.com', 'role' => 'admin']);

        $this->actingAs($boss)->get('/dashboard/users')->assertOk()
            ->assertSee('Users &amp; roles', false)
            ->assertSee('Adinda Melati')->assertSee('adinda@example.com')
            ->assertSee('Arvan Hariyanto')
            ->assertSee('<option value="system_admin"', false);

        $this->actingAs($boss)->get('/dashboard/users?q=arvan')->assertOk()
            ->assertSee('Arvan Hariyanto')->assertDontSee('Adinda Melati');

        $this->actingAs($boss)->get('/dashboard/users?role=user')->assertOk()
            ->assertSee('Adinda Melati')->assertDontSee('Arvan Hariyanto');
    }

    public function test_system_admin_can_change_a_role(): void
    {
        $boss = $this->as('system_admin');
        $person = $this->as('user');

        $this->actingAs($boss)->from('/dashboard/users')
            ->patch(route('dashboard.users.update', $person), ['role' => 'admin'])
            ->assertRedirect('/dashboard/users')
            ->assertSessionHas('success');
        $this->assertSame('admin', $person->fresh()->role);

        $this->actingAs($boss)->patch(route('dashboard.users.update', $person), ['role' => 'system_admin']);
        $this->assertSame('system_admin', $person->fresh()->role);
    }

    public function test_only_the_three_roles_are_allowed(): void
    {
        $boss = $this->as('system_admin');
        $person = $this->as('user');

        $this->actingAs($boss)->patch(route('dashboard.users.update', $person), ['role' => 'superhero'])
            ->assertSessionHasErrors('role');
        $this->assertSame('user', $person->fresh()->role);
    }

    public function test_system_admin_cannot_change_own_role_or_delete_self(): void
    {
        $boss = $this->as('system_admin');

        $this->actingAs($boss)->patch(route('dashboard.users.update', $boss), ['role' => 'user'])
            ->assertSessionHasErrors('role');
        $this->actingAs($boss)->delete(route('dashboard.users.destroy', $boss))
            ->assertSessionHasErrors();

        $this->assertSame('system_admin', $boss->fresh()->role);
    }

    public function test_system_admin_can_delete_a_user(): void
    {
        $boss = $this->as('system_admin');
        $person = $this->as('user');

        $this->actingAs($boss)->from('/dashboard/users')
            ->delete(route('dashboard.users.destroy', $person))
            ->assertRedirect('/dashboard/users')
            ->assertSessionHas('success');

        $this->assertModelMissing($person);
    }

    public function test_admins_and_users_cannot_change_roles_or_delete(): void
    {
        $target = $this->as('user');

        foreach (['user', 'admin'] as $role) {
            $actor = $this->as($role);
            $this->actingAs($actor)->patch(route('dashboard.users.update', $target), ['role' => 'system_admin'])->assertForbidden();
            $this->actingAs($actor)->delete(route('dashboard.users.destroy', $target))->assertForbidden();
        }

        $this->assertSame('user', $target->fresh()->role);
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/dashboard/users')->assertRedirect('/login');
    }

    public function test_logged_in_people_opening_login_page_go_to_their_home(): void
    {
        $this->actingAs($this->as('user'))->get('/login')->assertRedirect(url('/'));
        $this->actingAs($this->as('admin'))->get('/login')->assertRedirect(route('dashboard'));
        $this->actingAs($this->as('system_admin'))->get('/register')->assertRedirect(route('dashboard'));
    }
}
