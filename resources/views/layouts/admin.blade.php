@php use App\Authorization\Permissions; @endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.partials.favicon')
    <title>@yield('title', 'لوحة الإدارة — '.config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen min-h-[100dvh] touch-manipulation overflow-x-clip font-sans antialiased text-slate-800 bg-gradient-to-br from-emerald-50/90 via-stone-100 to-sky-50">
    @php
        $navLink = 'group flex min-h-11 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 ease-out active:bg-white/50 motion-reduce:transition-none';
        $navIdle = 'text-slate-700 hover:bg-white/60 hover:text-slate-900 active:scale-[0.98]';
        $navActive = 'bg-white/80 text-emerald-900 shadow-sm ring-1 ring-emerald-200/60';
        $navUser = auth()->user();
        $accountHref = $navUser?->isStudent()
            ? route('profile.edit')
            : ($navUser?->can(Permissions::USERS_MANAGE)
                ? route('admin.users.edit', $navUser)
                : null);
    @endphp
    {{-- ملاحة: .peer يليه مباشرة #admin-nav-panel حتى يُطبّق peer-checked:translate (محدد الشقيج المجاور +) --}}
    <div class="flex min-h-screen min-w-0 flex-col lg:flex-row">
        <input type="checkbox" id="admin-mobile-nav" class="peer sr-only" autocomplete="off" />
        <aside
            id="admin-nav-panel"
            class="z-40 flex h-full min-h-0 w-[min(20rem,calc(100vw-1.5rem))] min-w-0 shrink-0 flex-col border-l border-white/50 bg-white/40 shadow-lg shadow-emerald-900/5 backdrop-blur-xl transition-transform duration-300 ease-out will-change-transform [padding-inline-end:max(0px,env(safe-area-inset-right))] [padding-inline-start:max(0px,env(safe-area-inset-left))] max-lg:pb-[max(0.75rem,env(safe-area-inset-bottom))] max-lg:fixed max-lg:start-0 max-lg:top-0 max-lg:h-[100dvh] max-lg:max-h-[100dvh] max-lg:translate-x-full max-lg:peer-checked:translate-x-0 max-lg:shadow-2xl lg:sticky lg:top-0 lg:z-0 lg:h-screen lg:w-64 lg:max-w-none lg:shrink-0 lg:translate-x-0 lg:will-change-auto"
        >
            <div class="flex h-full min-h-0 flex-col gap-5 p-5 max-lg:pt-[max(0.75rem,env(safe-area-inset-top))]">
                <div class="flex shrink-0 items-center justify-between gap-3 border-b border-white/40 pb-3 lg:hidden">
                    <p class="text-sm font-semibold text-slate-800">القائمة</p>
                    <label
                        for="admin-mobile-nav"
                        class="inline-flex h-11 min-w-11 cursor-pointer items-center justify-center rounded-xl border border-white/60 bg-white/70 text-slate-600 shadow-sm touch-manipulation transition hover:bg-white/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400/80"
                        aria-label="إغلاق القائمة"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </label>
                </div>
                <div class="shrink-0 rounded-2xl border border-white/50 bg-gradient-to-br from-white/70 to-emerald-50/40 px-4 py-3.5 shadow-sm backdrop-blur-md">
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-500">نظام الخريجين</p>
                        <p class="truncate text-base font-semibold text-slate-800">لوحة الإدارة</p>
                    </div>
                </div>

                <nav data-nav-scroll class="flex min-h-0 flex-1 flex-col gap-0.5 overflow-y-auto overscroll-contain pr-0.5 text-sm font-medium" aria-label="التنقل الرئيسي">
                    @can(Permissions::DASHBOARD_VIEW)
                        <a href="{{ route('admin.dashboard') }}" class="{{ $navLink }} {{ request()->routeIs('admin.dashboard') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">الرئيسية والخريجون</span>
                        </a>
                        <a href="{{ route('admin.students.by-year') }}" class="{{ $navLink }} {{ request()->routeIs('admin.students.by-year', 'admin.profiles.show') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.students.by-year', 'admin.profiles.show') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">الطلاب حسب سنة التخرج</span>
                        </a>
                    @endcan
                    @can(Permissions::APPLICATIONS_MANAGE)
                        <a href="{{ route('admin.applications.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.applications.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.applications.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">طلبات التسجيل</span>
                        </a>
                    @endcan
                    @can(Permissions::CATALOG_MANAGE)
                        <a href="{{ route('admin.departments.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.departments.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.departments.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008H17.25v-.008Zm0 3.75h.008v.008H17.25v-.008Zm0 3.75h.008v.008H17.25v-.008Z" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">الأقسام والتخصصات</span>
                        </a>
                        <a href="{{ route('admin.graduation-years.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.graduation-years.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.graduation-years.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">سنوات التخرج</span>
                        </a>
                        <a href="{{ route('admin.universities.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.universities.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.universities.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M12 21c2.412 0 4.435-.388 6-1.167m0 11.334c-1.565-.779-3.588-1.167-6-1.167m6 1.167V10.332" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">الجامعات</span>
                        </a>
                    @endcan
                    @can(Permissions::USERS_MANAGE)
                        <a href="{{ route('admin.users.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.users.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.users.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">المستخدمون</span>
                        </a>
                    @endcan
                    @can(Permissions::ROLES_MANAGE)
                        <a href="{{ route('admin.roles.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.roles.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.roles.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">الأدوار والصلاحيات</span>
                        </a>
                    @endcan
                    @can(Permissions::ACTIVITY_LOG_VIEW)
                        <a href="{{ route('admin.activity.index') }}" class="{{ $navLink }} {{ request()->routeIs('admin.activity.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('admin.activity.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 4.5h12M3.75 6.75h.008v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">سجل النشاط</span>
                        </a>
                    @endcan
                    @if (auth()->user()?->isStudent())
                        <a href="{{ route('profile.edit') }}" class="{{ $navLink }} {{ request()->routeIs('profile.*') ? $navActive : $navIdle }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-emerald-700 {{ request()->routeIs('profile.*') ? 'text-emerald-700' : '' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span class="min-w-0 flex-1 leading-snug">ملفي الشخصي</span>
                        </a>
                    @endif
                    <a href="{{ route('applications.create') }}" class="{{ $navLink }} {{ $navIdle }}">
                        <svg class="h-5 w-5 shrink-0 text-slate-500 transition-colors duration-200 group-hover:text-sky-700" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="min-w-0 flex-1 leading-snug">نموذج التسجيل العام</span>
                    </a>
                </nav>

                <div class="shrink-0 space-y-3 border-t border-white/50 pt-4">
                    @if ($accountHref)
                        <a href="{{ $accountHref }}" class="flex items-center gap-3 rounded-xl border border-white/40 bg-white/35 px-3 py-2.5 shadow-sm backdrop-blur-sm transition hover:bg-white/55 hover:ring-1 hover:ring-emerald-200/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400/80" @if ($navUser?->isStudent()) aria-label="فتح الملف الشخصي" @else aria-label="تعديل بيانات الحساب" @endif>
                    @else
                        <div class="flex items-center gap-3 rounded-xl border border-white/40 bg-white/35 px-3 py-2.5 shadow-sm backdrop-blur-sm" aria-label="المستخدم الحالي">
                    @endif
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-200/80 text-slate-600 ring-1 ring-white/60" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                        <p class="min-w-0 flex-1 truncate text-sm font-medium text-slate-800" title="{{ $navUser?->name }}">{{ $navUser?->name }}</p>
                    @if ($accountHref)
                        </a>
                    @else
                        </div>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="w-full" aria-label="تسجيل الخروج">
                        @csrf
                        <button type="submit" class="flex w-full min-h-[2.75rem] items-center justify-center gap-2.5 rounded-xl border border-rose-400/30 bg-[rgba(206,39,39,1)] px-3 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-900/10 backdrop-blur transition duration-200 ease-out hover:border-rose-300/50 hover:bg-rose-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white/30 active:scale-[0.98] motion-reduce:transition-none motion-reduce:active:scale-100">
                            <span class="min-w-0">تسجيل الخروج</span>
                            <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <label
            for="admin-mobile-nav"
            class="fixed end-0 bottom-0 start-0 z-20 top-24 bg-slate-900/40 opacity-0 transition-opacity duration-300 pointer-events-none ease-out peer-checked:opacity-100 peer-checked:pointer-events-auto lg:hidden"
            aria-hidden="true"
        ></label>

        <div class="relative z-0 flex min-h-0 min-w-0 flex-1 flex-col">
            <header
                class="sticky top-0 z-10 flex min-h-14 shrink-0 items-center justify-between gap-3 border-b border-white/50 bg-white/60 px-4 py-3 pe-[max(1rem,env(safe-area-inset-right))] ps-4 pt-[max(0.5rem,env(safe-area-inset-top))] shadow-sm shadow-emerald-900/5 backdrop-blur-sm supports-[backdrop-filter]:bg-white/50 lg:hidden"
            >
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">نظام الخريجين</p>
                    <p class="truncate text-base font-semibold text-slate-800">لوحة الإدارة</p>
                </div>
                <label
                    for="admin-mobile-nav"
                    class="admin-mobile-menu-btn inline-flex h-11 min-w-11 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-white/60 bg-white/70 text-slate-700 shadow-sm touch-manipulation transition hover:bg-white/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400/80"
                    aria-label="فتح وإغلاق القائمة"
                >
                    <span class="admin-burger__open inline-flex h-5 w-5 items-center justify-center" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </span>
                    <span class="admin-burger__close inline-flex h-5 w-5 items-center justify-center" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </span>
                </label>
            </header>

        <main class="w-full min-w-0 flex-1 scroll-smooth overflow-x-auto p-4 sm:p-6 lg:p-10 max-lg:pb-[max(1.5rem,env(safe-area-inset-bottom))]">
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
    </div>
</body>
</html>
