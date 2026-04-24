@php
    use App\Models\Application;
    $user = $profile->user;
@endphp
@extends('layouts.admin')

@section('title', 'ملف الخريج — '.($user?->name ?? '—'))

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $user?->name ?? '—' }}</h1>
            <p class="mt-1 text-sm text-slate-600">عرض بيانات الخريج والمستندات (للاطلاع).</p>
        </div>
        <a
            href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.students.by-year') }}"
            class="shrink-0 rounded-xl border border-white/60 bg-white/50 px-4 py-2 text-sm font-semibold text-slate-800 backdrop-blur transition hover:bg-white/70"
        >رجوع</a>
    </div>

    <div class="space-y-6">
        <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg shadow-emerald-900/5 backdrop-blur-xl">
            <h2 class="text-lg font-semibold text-slate-900">التواصل والهوية</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium text-slate-500">البريد</dt>
                    <dd class="text-sm text-slate-900">{{ $user?->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">الهاتف</dt>
                    <dd class="text-sm text-slate-900">{{ $user?->phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">الرقم القومي</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $profile->national_id ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">المحافظة</dt>
                    <dd class="text-sm text-slate-900">
                        {{ $profile->governorate ? (Application::GOVERNORATES[$profile->governorate] ?? $profile->governorate) : '—' }}
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium text-slate-500">العنوان</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->address ? $profile->address : '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg shadow-emerald-900/5 backdrop-blur-xl">
            <h2 class="text-lg font-semibold text-slate-900">البيانات الأكاديمية</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium text-slate-500">الجامعة</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->university_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">القسم</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->department?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">التخصص</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->specialization?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">سنة التخرج</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->graduationYear?->year ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">التقدير</dt>
                    <dd class="text-sm text-slate-900">{{ Application::gradeLabels()[$profile->grade] ?? $profile->grade ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">المعدل</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->gpa !== null ? number_format((float) $profile->gpa, 2) : '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg shadow-emerald-900/5 backdrop-blur-xl">
            <h2 class="text-lg font-semibold text-slate-900">العمل والتجنيد</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-slate-500">حالة العمل</dt>
                    <dd class="text-sm text-slate-900">{{ Application::employmentStatusLabels()[$profile->employment_status] ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">التجنيد</dt>
                    <dd class="text-sm text-slate-900">{{ Application::militaryExemptionLabel($profile->exempt_from_military) }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg shadow-emerald-900/5 backdrop-blur-xl">
            <h2 class="text-lg font-semibold text-slate-900">المهارات والشهادات (نص)</h2>
            <div class="mt-4 space-y-3 text-sm text-slate-800">
                <p><span class="font-medium text-slate-600">المهارات:</span> {{ $profile->skills ? $profile->skills : '—' }}</p>
                <p><span class="font-medium text-slate-600">شهادات ودورات:</span> {{ $profile->certificates_text ? $profile->certificates_text : '—' }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg shadow-emerald-900/5 backdrop-blur-xl">
            <h2 class="text-lg font-semibold text-slate-900">المستندات</h2>
            <ul class="mt-3 space-y-2 text-sm">
                <li>
                    <span class="text-slate-600">السيرة الذاتية:</span>
                    @if ($profile->cv_path)
                        <a href="{{ asset('storage/'.ltrim($profile->cv_path, '/')) }}" target="_blank" rel="noopener" class="font-semibold text-emerald-700 underline hover:text-emerald-900">عرض / تحميل</a>
                    @else
                        <span class="text-slate-500">—</span>
                    @endif
                </li>
                <li>
                    <span class="text-slate-600">الشهادة:</span>
                    @if ($profile->cert_path)
                        <a href="{{ asset('storage/'.ltrim($profile->cert_path, '/')) }}" target="_blank" rel="noopener" class="font-semibold text-emerald-700 underline hover:text-emerald-900">عرض / تحميل</a>
                    @else
                        <span class="text-slate-500">—</span>
                    @endif
                </li>
                <li>
                    <span class="text-slate-600">الصورة الشخصية:</span>
                    @if ($profile->photo_path)
                        <a href="{{ asset('storage/'.ltrim($profile->photo_path, '/')) }}" target="_blank" rel="noopener" class="font-semibold text-emerald-700 underline hover:text-emerald-900">عرض / تحميل</a>
                    @else
                        <span class="text-slate-500">—</span>
                    @endif
                </li>
            </ul>
        </div>
    </div>
@endsection
