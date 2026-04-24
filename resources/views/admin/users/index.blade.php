@extends('layouts.admin')

@section('title', 'المستخدمون')

@section('content')
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">المستخدمون</h1>
            <p class="mt-1 text-sm text-slate-600">إنشاء وتعديل وحذف حسابات النظام والأدوار.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">مستخدم جديد</a>
    </div>

    @if ($errors->has('delete'))
        <div class="mb-4 rounded-2xl border border-rose-200/70 bg-rose-50/85 px-4 py-3 text-sm text-rose-900">{{ $errors->first('delete') }}</div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-white/50 bg-white/45 shadow-xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/50 text-sm">
                <thead class="bg-white/50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">البريد</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">الدور</th>
                        <th class="px-4 py-3 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40 bg-white/30">
                    @foreach ($users as $user)
                        <tr class="hover:bg-white/40">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->phone ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-sky-100/80 px-2 py-1 text-xs font-semibold text-sky-900">{{ \App\Models\User::roleLabel($user->role) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-white/60 bg-white/60 px-3 py-1 text-xs font-semibold text-slate-800 hover:bg-white/80">تعديل</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('حذف هذا المستخدم؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-600 px-3 py-1 text-xs font-semibold text-white hover:bg-rose-700">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="border-t border-white/50 bg-white/40 px-4 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
