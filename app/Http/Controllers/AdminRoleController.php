<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminRoleController extends Controller
{
    /**
     * @return list<string>
     */
    private function protectedRoleNames(): array
    {
        return [User::ROLE_ADMIN, User::ROLE_REVIEWER, User::ROLE_STUDENT];
    }

    private function permissionTable(): string
    {
        return config('permission.table_names.permissions');
    }

    private function roleTable(): string
    {
        return config('permission.table_names.roles');
    }

    private function webPermissionNames(): array
    {
        return Permission::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }

    public function index(): View
    {
        $roles = Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function createRole(): View
    {
        return view('admin.roles.create-role');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9][a-z0-9._-]*$/i',
                Rule::unique($this->roleTable(), 'name')->where('guard_name', 'web'),
            ],
        ]);

        Role::query()->create([
            'name' => strtolower($validated['name']),
            'guard_name' => 'web',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('status', __('تم إنشاء الدور.'));
    }

    public function createPermission(): View
    {
        return view('admin.roles.create-permission');
    }

    public function storePermission(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9][a-z0-9._-]*$/i',
                Rule::unique($this->permissionTable(), 'name')->where('guard_name', 'web'),
            ],
        ]);

        $name = strtolower($validated['name']);
        Permission::findOrCreate($name, 'web');

        Role::query()
            ->where('name', User::ROLE_ADMIN)
            ->where('guard_name', 'web')
            ->first()
            ?->syncPermissions($this->webPermissionNames());

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('status', __('تم إنشاء الصلاحية وتحديث دور المدير ليشملها.'));
    }

    public function edit(Role $role): View
    {
        $permissionNames = $this->webPermissionNames();
        $assigned = $role->permissions->pluck('name')->all();

        return view('admin.roles.edit', compact('role', 'permissionNames', 'assigned'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->name === User::ROLE_ADMIN) {
            $role->syncPermissions($this->webPermissionNames());

            return redirect()
                ->route('admin.roles.index')
                ->with('status', __('دور المدير يمتلك جميع الصلاحيات المعرّفة في النظام.'));
        }

        if ($role->name === User::ROLE_STUDENT) {
            $role->syncPermissions();

            return redirect()
                ->route('admin.roles.index')
                ->with('status', __('دور الخريج مخصص لحسابات الخريجين ولا يحمل صلاحيات إدارية.'));
        }

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists($this->permissionTable(), 'name')->where('guard_name', 'web'),
            ],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('status', __('تم تحديث صلاحيات الدور.'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (in_array($role->name, $this->protectedRoleNames(), true)) {
            return redirect()
                ->route('admin.roles.index')
                ->withErrors(['delete' => __('لا يمكن مسح الأدوار الأساسية (مدير، مراجع، خريج).')]);
        }

        if (User::role($role->name)->count() > 0) {
            return redirect()
                ->route('admin.roles.index')
                ->withErrors(['delete' => __('لا يمكن مسح الدور لوجود مستخدمين مرتبطين به. غيّر أدوارهم أولاً.')]);
        }

        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('status', __('تم مسح الدور.'));
    }
}
