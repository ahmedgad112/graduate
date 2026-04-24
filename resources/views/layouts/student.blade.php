<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ملف الخريج — '.config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen min-h-[100dvh] touch-manipulation overflow-x-clip font-sans antialiased text-slate-800 bg-gradient-to-br from-emerald-50/90 via-stone-100 to-sky-50">
    <header class="sticky top-0 z-30 border-b border-white/50 bg-white/40 pt-[max(0px,env(safe-area-inset-top))] backdrop-blur-md supports-[backdrop-filter]:bg-white/50">
        <div class="mx-auto flex w-full min-w-0 max-w-5xl flex-col gap-3 px-4 py-3 pe-[max(1rem,env(safe-area-inset-right))] ps-4 sm:gap-4 sm:py-4 md:flex-row md:items-center md:justify-between">
            <div class="min-w-0 md:pr-2">
                <p class="text-xs font-medium text-slate-500">بوابة الخريجين</p>
                <p class="text-lg font-semibold text-slate-900 md:truncate" title="{{ auth()->user()?->name }}">مرحباً، {{ auth()->user()?->name }}</p>
            </div>
            <nav
                class="-mx-1 flex w-full min-w-0 flex-nowrap items-stretch gap-2 overflow-x-auto overflow-y-hidden overscroll-x-contain scroll-smooth px-1 py-0.5 text-sm font-medium [touch-action:pan-x] [scrollbar-color:rgba(16,24,20,0.2)_transparent] [scrollbar-width:thin] sm:gap-2.5 sm:py-0 md:justify-end md:overflow-x-visible md:pl-0 [&::-webkit-scrollbar]:h-1"
            >
                <a href="{{ route('profile.edit') }}" class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-xl px-4 py-2.5 whitespace-nowrap sm:px-3 sm:py-2 {{ request()->routeIs('profile.*') ? 'bg-white/70 shadow-sm ring-1 ring-white/60' : 'hover:bg-white/50' }}">ملفي</a>
                <a href="{{ route('applications.create') }}" class="inline-flex min-h-11 min-w-0 shrink-0 items-center justify-center rounded-xl px-4 py-2.5 text-slate-600 whitespace-nowrap hover:bg-white/50 sm:px-3 sm:py-2">نموذج تسجيل جديد</a>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="min-h-11 w-full min-w-[8.5rem] rounded-xl bg-rose-500/90 px-4 py-2.5 text-sm font-semibold text-white shadow-sm touch-manipulation hover:bg-rose-600 sm:min-w-0 sm:px-3 sm:py-2">تسجيل الخروج</button>
                </form>
            </nav>
        </div>
    </header>
    <main class="mx-auto w-full min-w-0 max-w-5xl scroll-smooth px-4 py-6 pe-[max(1rem,env(safe-area-inset-right))] pb-[max(1.5rem,env(safe-area-inset-bottom))] sm:py-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200/60 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-900 shadow-sm">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200/70 bg-rose-50/85 px-4 py-3 text-sm text-rose-900">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
