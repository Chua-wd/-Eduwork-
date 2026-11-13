<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * User management (system admins only): list people, change their role, delete them.
 * /dashboard/users
 */
class UserController extends Controller
{
    // GET /dashboard/users?q=...&dir=asc|desc
    public function index(Request $request)
    {
        $dir = $request->dir === 'desc' ? 'desc' : 'asc';

        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(fn ($q) => $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%'));
            })
            ->when($request->filled('role') && array_key_exists($request->role, User::ROLES), fn ($query) => $query->where('role', $request->role))
            ->orderBy('name', $dir)
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.users.index', [
            'users' => $users,
            'dir' => $dir,
            'roles' => User::ROLES,
        ]);
    }

    // PATCH /dashboard/users/{user} : change the role (the dropdown in the table)
    public function update(Request $request, User $user)
    {
        $data = $request->validate(['role' => ['required', Rule::in(array_keys(User::ROLES))]]);

        // Changing your own role could lock everyone out of User management
        if ($user->is($request->user())) {
            return back()->withErrors(['role' => "You can't change your own role."]);
        }

        $user->role = $data['role'];
        $user->save();

        return back()->with('success', "{$user->name} is now {$user->roleLabel()}.");
    }

    // DELETE /dashboard/users/{user}
    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['role' => "You can't delete your own account here."]);
        }

        $name = $user->name;
        $user->delete(); // their orders go too (orders.user_id is "cascade on delete")

        return back()->with('success', "{$name} was deleted.");
    }
}
