@extends('layouts.admin')

@section('title', 'طلبات التسجيل')

@section('content')
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-lg font-bold text-slate-900 sm:text-xl">طلبات قيد المراجعة</h1>
            <p class="mt-0.5 text-xs text-slate-600">الطلبات ذات الحالة «معلّقة» فقط.</p>
        </div>
    </div>

    @if ($applications->isNotEmpty())
        <div class="space-y-2.5">
            @foreach ($applications as $application)
                <article
                    class="overflow-hidden rounded-2xl border border-white/50 bg-white/45 shadow-md shadow-emerald-900/5 backdrop-blur-xl"
                >
                    <div class="border-b border-white/50 bg-white/40 px-3 py-2 backdrop-blur-md sm:px-4 sm:py-2.5">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h2 class="text-sm font-bold text-slate-900 sm:text-base">{{ $application->name }}</h2>
                                <p class="truncate text-xs text-slate-600">{{ $application->email }}</p>
                            </div>
                            <div class="flex w-full flex-wrap items-center justify-end gap-1.5 sm:w-auto sm:justify-start">
                                <a
                                    href="{{ route('admin.applications.show', $application) }}"
                                    class="rounded-md border border-white/60 bg-white/70 px-2 py-1 text-[11px] font-semibold text-slate-800 shadow-sm hover:bg-white"
                                >تفاصيل</a>
                                <form method="POST" action="{{ route('admin.applications.reject', $application) }}" onsubmit="return confirm('تأكيد رفض الطلب؟');">
                                    @csrf
                                    <button type="submit" class="rounded-md bg-rose-600 px-2 py-1 text-[11px] font-semibold text-white shadow-sm hover:bg-rose-700">رفض</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="p-2 sm:p-2.5">
                        <dl class="grid grid-cols-2 gap-x-2 gap-y-1 text-[10px] leading-tight sm:grid-cols-3 lg:grid-cols-4 sm:gap-y-1.5">
                            <div class="min-w-0">
                                <dt class="text-[9px] text-slate-500">الهاتف</dt>
                                <dd class="mt-px break-words text-slate-800">{{ $application->phone }}</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-[9px] text-slate-500">الجامعة</dt>
                                <dd class="mt-px break-words text-slate-800">{{ $application->university?->name ?? '—' }}</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-[9px] text-slate-500">القسم</dt>
                                <dd class="mt-px break-words text-slate-800">{{ $application->department?->name ?? '—' }}</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-[9px] text-slate-500">التخصص</dt>
                                <dd class="mt-px break-words text-slate-800">{{ $application->specialization?->name ?? '—' }}</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-[9px] text-slate-500">سنة التخرج</dt>
                                <dd class="mt-px break-words text-slate-800">{{ $application->graduationYear?->year ?? '—' }}</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-[9px] text-slate-500">التقدير</dt>
                                <dd class="mt-px break-words text-slate-800">{{ \App\Models\Application::gradeLabels()[$application->grade] ?? $application->grade }}</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-[9px] text-slate-500">العمل</dt>
                                <dd class="mt-px break-words text-slate-800">{{ \App\Models\Application::employmentStatusLabels()[$application->employment_status] ?? '—' }}</dd>
                            </div>
                            <div class="min-w-0 sm:col-span-2 lg:col-span-1">
                                <dt class="text-[9px] text-slate-500">التجنيد</dt>
                                <dd class="mt-px break-words text-slate-800">{{ \App\Models\Application::militaryExemptionLabel($application->exempt_from_military) }}</dd>
                            </div>
                        </dl>

                        <div class="mt-1.5">
                            <p class="text-[9px] font-semibold text-slate-500">المرفقات</p>
                            <div class="mt-0.5 flex flex-wrap gap-1">
                                @if ($application->cv_path)
                                    <a href="{{ asset('storage/'.$application->cv_path) }}" target="_blank" class="inline-flex items-center rounded bg-emerald-600/90 px-1.5 py-px text-[10px] font-semibold text-white hover:bg-emerald-700">السيرة</a>
                                @endif
                                @if ($application->cert_path)
                                    <a href="{{ asset('storage/'.$application->cert_path) }}" target="_blank" class="inline-flex items-center rounded bg-sky-600/90 px-1.5 py-px text-[10px] font-semibold text-white hover:bg-sky-700">الشهادة</a>
                                @endif
                                @if ($application->photo_path)
                                    <a href="{{ asset('storage/'.$application->photo_path) }}" target="_blank" class="inline-flex items-center rounded bg-stone-600/90 px-1.5 py-px text-[10px] font-semibold text-white hover:bg-stone-700">الصورة</a>
                                @endif
                                @if (! $application->cv_path && ! $application->cert_path && ! $application->photo_path)
                                    <span class="text-[10px] text-slate-500">لا توجد مرفقات</span>
                                @endif
                            </div>
                        </div>

                        @if ($application->skills || $application->certificates_text)
                            <div class="mt-1.5 text-[10px] leading-snug text-slate-600">
                                @if ($application->skills)
                                    <p><span class="font-semibold text-slate-700">المهارات:</span> {{ $application->skills }}</p>
                                @endif
                                @if ($application->certificates_text)
                                    <p class="mt-1"><span class="font-semibold text-slate-700">شهادات ودورات:</span> {{ $application->certificates_text }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-white/50 bg-white/45 shadow-md shadow-emerald-900/5 backdrop-blur-xl">
            <p class="px-4 py-8 text-center text-sm text-slate-600">لا توجد طلبات معلّقة حالياً.</p>
        </div>
    @endif

    @if ($applications->hasPages())
        <div class="mt-4 overflow-hidden rounded-2xl border border-white/50 bg-white/40 px-3 py-2 shadow-sm backdrop-blur-md">
            {{ $applications->links() }}
        </div>
    @endif
@endsection
