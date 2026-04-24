@extends('layouts.admin')

@php use App\Authorization\Permissions; @endphp

@section('title', 'الأدوار والصلاحيات')

@section('content')
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">الأدوار والصلاحيات</h1>
            <p class="mt-1 text-sm text-slate-600">كل دور يحدد ما يمكن للمستخدم فعله في لوحة الإدارة (عدا دور المدير الذي يملك كل الصلاحيات المعرّفة).</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.roles.create') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">إنشاء دور</a>
            <a href="{{ route('admin.permissions.create') }}" class="rounded-xl border border-emerald-600/80 bg-white/50 px-4 py-2 text-sm font-semibold text-emerald-800 shadow-sm hover:bg-white/80">إنشاء صلاحية</a>
        </div>
    </div>

    @if ($errors->has('delete'))
        <div class="mb-4 rounded-2xl border border-rose-200/70 bg-rose-50/85 px-4 py-3 text-sm text-rose-900">{{ $errors->first('delete') }}</div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-white/50 bg-white/45 shadow-xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/50 text-sm">
                <thead class="bg-white/50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">الدور</th>
                        <th class="px-4 py-3">الصلاحيات</th>
                        <th class="px-4 py-3 text-center">إجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40 bg-white/30">
                    @foreach ($roles as $role)
                        <tr class="hover:bg-white/40">
                            <td class="px-4 py-3 font-medium text-slate-900">
                                @if (in_array($role->name, \App\Models\User::assignableRoles(), true))
                                    {{ \App\Models\User::roleLabel($role->name) }}
                                    <span class="mr-2 text-xs font-normal text-slate-500">({{ $role->name }})</span>
                                @else
                                    {{ $role->name }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($role->permissions as $permission)
                                        <span class="rounded-full bg-emerald-100/90 px-2 py-0.5 text-xs font-medium text-emerald-900">{{ Permissions::label($permission->name) }}</span>
                                    @empty
                                        <span class="text-xs text-slate-500">لا صلاحيات إدارية</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="rounded-lg border border-white/60 bg-white/60 px-3 py-1 text-xs font-semibold text-slate-800 hover:bg-white/80">تعديل الصلاحيات</a>
                                    @if (! in_array($role->name, \App\Models\User::assignableRoles(), true))
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('مسح هذا الدور نهائياً؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-rose-600/90 px-3 py-1 text-xs font-semibold text-white hover:bg-rose-700">مسح</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
