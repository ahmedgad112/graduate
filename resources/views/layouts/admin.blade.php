<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'لوحة الإدارة — '.config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased text-slate-800 bg-gradient-to-br from-emerald-50/90 via-stone-100 to-sky-50">
    <div class="flex min-h-screen">
        <aside class="w-64 shrink-0 border-l border-white/40 bg-white/35 backdrop-blur-xl shadow-lg shadow-emerald-900/5">
            <div class="flex h-full flex-col gap-8 p-6">
                <div class="rounded-2xl border border-white/50 bg-white/50 px-4 py-3 shadow-sm backdrop-blur-md">
                    <p class="text-xs font-medium text-slate-500">نظام الخريجين</p>
                    <p class="text-lg font-semibold text-slate-800">لوحة الإدارة</p>
                </div>
                <nav class="flex flex-col gap-1 text-sm font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl px-3 py-2 transition hover:bg-white/60 {{ request()->routeIs('admin.dashboard') ? 'bg-white/70 shadow-sm ring-1 ring-white/60' : '' }}">الرئيسية والخريجون</a>
                    <a href="{{ route('admin.students.by-year') }}" class="rounded-xl px-3 py-2 transition hover:bg-white/60 {{ request()->routeIs('admin.students.by-year') ? 'bg-white/70 shadow-sm ring-1 ring-white/60' : '' }}">الطلاب حسب سنة التخرج</a>
                    <a href="{{ route('admin.applications.index') }}" class="rounded-xl px-3 py-2 transition hover:bg-white/60 {{ request()->routeIs('admin.applications.*') ? 'bg-white/70 shadow-sm ring-1 ring-white/60' : '' }}">طلبات التسجيل</a>
                    <a href="{{ route('admin.departments.index') }}" class="rounded-xl px-3 py-2 transition hover:bg-white/60 {{ request()->routeIs('admin.departments.*') ? 'bg-white/70 shadow-sm ring-1 ring-white/60' : '' }}">الأقسام والتخصصات</a>
                    <a href="{{ route('admin.graduation-years.index') }}" class="rounded-xl px-3 py-2 transition hover:bg-white/60 {{ request()->routeIs('admin.graduation-years.*') ? 'bg-white/70 shadow-sm ring-1 ring-white/60' : '' }}">سنوات التخرج</a>
                    <a href="{{ route('admin.universities.index') }}" class="rounded-xl px-3 py-2 transition hover:bg-white/60 {{ request()->routeIs('admin.universities.*') ? 'bg-white/70 shadow-sm ring-1 ring-white/60' : '' }}">الجامعات</a>
                    <a href="{{ route('applications.create') }}" class="rounded-xl px-3 py-2 transition hover:bg-white/60">نموذج التسجيل العام</a>
                </nav>
                <div class="mt-auto space-y-2 border-t border-white/40 pt-4">
                    <p class="truncate text-xs text-slate-500">{{ auth()->user()?->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-rose-500/90 px-3 py-2 text-sm font-semibold text-white shadow-sm backdrop-blur transition hover:bg-rose-600">تسجيل الخروج</button>
                    </form>
                </div>
            </div>
        </aside>
        <main class="flex-1 overflow-x-auto p-6 lg:p-10">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200/60 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-900 shadow-sm backdrop-blur-md">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200/70 bg-rose-50/85 px-4 py-3 text-sm text-rose-900 shadow-sm backdrop-blur-md">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
