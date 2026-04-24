@extends('layouts.admin')

@php use App\Authorization\Permissions; @endphp

@section('title', 'تعديل صلاحيات الدور')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">تعديل صلاحيات الدور</h1>
        <a href="{{ route('admin.roles.index') }}" class="mt-2 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">← العودة للقائمة</a>
    </div>

    <div class="max-w-2xl rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
        <p class="mb-4 text-sm font-medium text-slate-800">
            @if (in_array($role->name, \App\Models\User::assignableRoles(), true))
                {{ \App\Models\User::roleLabel($role->name) }}
                <span class="text-slate-500">({{ $role->name }})</span>
            @else
                {{ $role->name }}
            @endif
        </p>

        @if ($role->name === \App\Models\User::ROLE_ADMIN)
            <p class="text-sm leading-relaxed text-slate-600">دور المدير يمتلك <strong>جميع</strong> الصلاحيات تلقائياً ولا يمكن تقييده من هذه الشاشة.</p>
            <ul class="mt-4 list-inside list-disc space-y-1 text-sm text-slate-700">
                @foreach ($permissionNames as $perm)
                    <li>{{ Permissions::label($perm) }}</li>
                @endforeach
            </ul>
        @elseif ($role->name === \App\Models\User::ROLE_STUDENT)
            <p class="text-sm leading-relaxed text-slate-600">حسابات الخريجين تستخدم هذا الدور للدخول إلى النظام عند الحاجة دون صلاحيات لوحة الإدارة. لا تُضاف هنا صلاحيات إدارية.</p>
        @else
            <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-3">
                @csrf
                @method('PUT')
                <p class="text-xs text-slate-500">حدد الصلاحيات الممنوحة لهذا الدور.</p>
                <div class="space-y-2 rounded-2xl border border-white/60 bg-white/40 p-4">
                    @foreach ($permissionNames as $perm)
                        <label class="flex cursor-pointer items-start gap-3 text-sm text-slate-800">
                            <input type="checkbox" name="permissions[]" value="{{ $perm }}" @checked(in_array($perm, $assigned, true)) class="mt-1 rounded border-white/60 text-emerald-600 focus:ring-emerald-500" />
                            <span>
                                <span class="font-medium">{{ Permissions::label($perm) }}</span>
                                <span class="mr-1 block text-xs text-slate-500">{{ $perm }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">حفظ الصلاحيات</button>
            </form>
        @endif
    </div>
@endsection
