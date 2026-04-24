<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with('roles')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::query()->where('guard_name', 'web')->orderBy('name')->pluck('name');

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:32', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => [
                'required',
                'string',
                Rule::exists(config('permission.table_names.roles'), 'name')->where('guard_name', 'web'),
            ],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('تم إنشاء المستخدم.'));
    }

    public function edit(User $user): View
    {
        $roles = Role::query()->where('guard_name', 'web')->orderBy('name')->pluck('name');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:32', Rule::unique('users', 'phone')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => [
                'required',
                'string',
                Rule::exists(config('permission.table_names.roles'), 'name')->where('guard_name', 'web'),
            ],
        ]);

        if ($user->id === $request->user()->id && $validated['role'] !== User::ROLE_ADMIN) {
            return back()
                ->withErrors(['role' => __('لا يمكنك إزالة دور المدير عن حسابك الحالي.')])
                ->withInput();
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('تم تحديث المستخدم.'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors(['delete' => __('لا يمكنك حذف حسابك الحالي.')]);
        }

        if ($user->hasRole(User::ROLE_ADMIN)) {
            $admins = User::role(User::ROLE_ADMIN)->count();
            if ($admins <= 1) {
                return redirect()
                    ->route('admin.users.index')
                    ->withErrors(['delete' => __('لا يمكن حذف آخر مدير في النظام.')]);
            }
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('تم حذف المستخدم.'));
    }
}
