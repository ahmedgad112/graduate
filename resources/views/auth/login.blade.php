@extends('layouts.app')

@section('title', 'دخول الإدارة')

@section('content')
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(16,185,129,0.12),transparent_45%),radial-gradient(circle_at_70%_10%,rgba(14,165,233,0.1),transparent_40%)]"></div>
        <div class="relative w-full max-w-md rounded-3xl border border-white/55 bg-white/45 p-8 shadow-2xl shadow-emerald-900/10 backdrop-blur-2xl">
            <h1 class="text-xl font-bold text-slate-900">تسجيل الدخول</h1>
            <p class="mt-1 text-sm text-slate-600">حسابات الإدارة والمراجعة، أو حساب الخريج لعرض ملفه وتحديث بياناته.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-rose-200/70 bg-rose-50/85 px-4 py-3 text-sm text-rose-900">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">كلمة المرور</label>
                    <input type="password" name="password" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="remember" value="1" class="rounded border-white/60 text-emerald-600 focus:ring-emerald-500" />
                    تذكرني
                </label>
                <button type="submit" class="w-full rounded-xl bg-slate-900 py-2.5 text-sm font-semibold text-white shadow-lg hover:bg-slate-800">دخول</button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-500">
                <a href="{{ route('applications.create') }}" class="font-medium text-emerald-700 hover:text-emerald-800">العودة لنموذج تسجيل الخريجين</a>
            </p>
        </div>
    </div>
@endsection
