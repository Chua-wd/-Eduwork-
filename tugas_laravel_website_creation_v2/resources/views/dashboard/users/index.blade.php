<x-admin-layout>
    <x-slot name="title">User Management</x-slot>

    <x-slot name="header">
        <div class="page-head">
            <div>
                <p class="eyebrow">( dashboard )</p>
                <h1 class="display display-lg">User Management</h1>
            </div>
        </div>
    </x-slot>

    <div class="alert" style="display: flex; gap: 8px; align-items: center;">
        <x-icon name="info" />
        <span><b>System Admin only.</b> Choose who can do what in the shop, or remove an account.</span>
    </div>

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <section class="pill" style="padding: 22px;">
        <h2 class="card-title" style="margin: 0;">Users &amp; roles</h2>
        <p class="muted" style="margin: 6px 0 18px; font-size: 14px; max-width: 900px;">
            Pick a role to change what a person can open. <b>User</b>: the shop, cart and profile.
            <b>Admin</b>: also the dashboard, products and product categories.
            <b>System Admin</b>: everything, including this page. The change is saved right away.
        </p>

        {{-- Search by name/email + filter by role --}}
        <form method="GET" action="{{ route('dashboard.users.index') }}" class="user-filters">
            <label class="side-search user-search">
                <x-icon name="search" size="14" />
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name or email..." aria-label="Search by name or email">
            </label>
            <select name="role" class="role-select" aria-label="Filter by role" onchange="this.form.submit()">
                <option value="">All roles</option>
                @foreach ($roles as $value => $label)
                    <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="hidden" name="dir" value="{{ $dir }}">
            <noscript><button type="submit" class="btn btn-ghost btn-sm">Filter</button></noscript>
            @if (request()->filled('q') || request()->filled('role'))
                <a href="{{ route('dashboard.users.index') }}" class="chip">Clear &times;</a>
            @endif
        </form>

        <div class="table-wrap" style="margin-top: 16px;">
            <table class="table users-table">
                <thead>
                    <tr>
                        <th>
                            {{-- Click to sort A-Z / Z-A --}}
                            <a href="{{ route('dashboard.users.index', array_merge(request()->query(), ['dir' => $dir === 'asc' ? 'desc' : 'asc', 'page' => null])) }}"
                               class="sort-link" aria-label="Sort by name {{ $dir === 'asc' ? 'Z to A' : 'A to Z' }}">
                                Name <span aria-hidden="true">{{ $dir === 'asc' ? '↑' : '↓' }}</span>
                            </a>
                        </th>
                        <th>Email</th>
                        <th>Role</th>
                        <th><span class="sr-only">Delete</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $initials = collect(explode(' ', $user->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
                            $isMe = $user->is(auth()->user());
                        @endphp
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span class="avatar avatar-sm">{{ mb_strtoupper($initials) }}</span>
                                    <span style="font-weight: 600;">{{ $user->name }}</span>
                                    @if ($isMe)
                                        <span class="tag">you</span>
                                    @endif
                                </div>
                            </td>
                            <td class="muted">{{ $user->email }}</td>
                            <td>
                                @if ($isMe)
                                    {{-- Your own role can't be changed here (you could lock yourself out) --}}
                                    <span class="role-select is-static" title="You can't change your own role">{{ $user->roleLabel() }}</span>
                                @else
                                    <form method="POST" action="{{ route('dashboard.users.update', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="role-select" aria-label="Role for {{ $user->name }}" onchange="this.form.submit()">
                                            @foreach ($roles as $value => $label)
                                                <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <noscript><button type="submit" class="btn btn-ghost btn-sm">Save</button></noscript>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @unless ($isMe)
                                    <div class="row-actions">
                                        <details class="confirm-delete">
                                            <summary class="trash-btn" title="Delete {{ $user->name }}" aria-label="Delete {{ $user->name }}"><x-icon name="trash" /></summary>
                                            <form action="{{ route('dashboard.users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Their orders are deleted too">Yes, delete</button>
                                            </form>
                                        </details>
                                    </div>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </section>
</x-admin-layout>
