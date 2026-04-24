@extends('layouts.admin')

@section('title', 'مستخدم جديد')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">إضافة مستخدم</h1>
        <a href="{{ route('admin.users.index') }}" class="mt-2 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">← العودة للقائمة</a>
    </div>

    <div class="max-w-xl rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">الاسم</label>
                <input name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">الهاتف (اختياري)</label>
                <input name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">كلمة المرور</label>
                <input type="password" name="password" required autocomplete="new-password" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">الدور</label>
                <select name="role" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                    @foreach ($roles as $roleValue)
                        <option value="{{ $roleValue }}" @selected(old('role') === $roleValue)>{{ \App\Models\User::roleLabel($roleValue) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">حفظ</button>
        </form>
    </div>
@endsection
