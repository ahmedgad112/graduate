@extends('layouts.admin')

@php
    use App\Models\Application;

    $statusLabel = Application::statusLabels()[$application->status] ?? $application->status;
    $statusClass = match ($application->status) {
        Application::STATUS_APPROVED => 'border-emerald-200/80 bg-emerald-50/90 text-emerald-900',
        Application::STATUS_REJECTED => 'border-rose-200/80 bg-rose-50/90 text-rose-900',
        default => 'border-amber-200/80 bg-amber-50/90 text-amber-950',
    };
@endphp

@section('title', 'طلب تسجيل: '.$application->name)

@section('content')
    <div class="mx-auto max-w-5xl">
        {{-- رأس الصفحة --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-start sm:justify-between">
            <div class="min-w-0">
                <a href="{{ route('admin.applications.index') }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-900">
                    ← العودة لقائمة الطلبات
                </a>
                <h1 class="mt-3 text-xl font-bold text-slate-900 sm:text-2xl">تفاصيل طلب التسجيل</h1>
                <p class="mt-1 text-sm text-slate-600">بيانات مقدّم الطلب والمرفقات.</p>
            </div>
            <div class="shrink-0 rounded-2xl border px-4 py-2.5 text-sm backdrop-blur-md {{ $statusClass }}">
                <span class="text-slate-600/90">حالة الطلب:</span>
                <span class="mr-2 font-semibold">{{ $statusLabel }}</span>
            </div>
        </div>

        {{--
            شبكة: على الشاشات الكبيرة عمود ضيق للصورة (13rem) وباقي المساحة للبيانات.
            على الموبايل: صورة صغيرة في أعلى الصفحة دون أن تشغل عرض الشاشة بالكامل.
        --}}
        <div class="grid gap-6 lg:grid-cols-[13rem_minmax(0,1fr)] lg:items-start">
            <aside class="min-w-0 lg:sticky lg:top-6">
                <div class="mx-auto w-full max-w-[13rem] rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg shadow-emerald-900/5 backdrop-blur-xl lg:mx-0">
                    <h2 class="mb-3 text-center text-xs font-bold uppercase tracking-wide text-slate-500 lg:text-right">الصورة الشخصية</h2>
                    @if ($application->photo_path)
                        <div class="mx-auto w-fit overflow-hidden rounded-xl border border-white/60 bg-white/70 shadow-inner">
                            <img
                                src="{{ asset('storage/'.$application->photo_path) }}"
                                alt="صورة {{ $application->name }}"
                                width="96"
                                height="120"
                                class="block h-24 w-20 object-cover object-top sm:h-28 sm:w-24"
                            />
                        </div>
                        <a
                            href="{{ asset('storage/'.$application->photo_path) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-3 block text-center text-xs font-medium text-emerald-700 hover:text-emerald-900 lg:text-right"
                        >
                            فتح بحجم كامل
                        </a>
                    @else
                        <p class="text-center text-xs text-slate-500 lg:text-right">لا توجد صورة.</p>
                    @endif
                </div>
            </aside>

            <div class="min-w-0 space-y-5">
                @php
                    $card = 'rounded-2xl border border-white/50 bg-white/45 p-5 shadow-md shadow-emerald-900/5 backdrop-blur-xl sm:p-6';
                    $sectionTitle = 'mb-4 border-b border-white/50 pb-2 text-sm font-bold text-slate-800';
                    $fieldBox = 'rounded-xl border border-white/40 bg-white/30 px-3 py-2.5';
                @endphp

                <section class="{{ $card }}">
                    <h2 class="{{ $sectionTitle }}">البيانات الشخصية</h2>
                    <dl class="grid gap-2.5 text-sm sm:grid-cols-2">
                        <div class="{{ $fieldBox }} sm:col-span-2">
                            <dt class="text-[11px] font-semibold text-slate-500">الاسم</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->name }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">البريد</dt>
                            <dd class="mt-0.5 break-all font-medium text-slate-900">{{ $application->email }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">الهاتف</dt>
                            <dd class="mt-0.5 text-right font-medium text-slate-900" dir="ltr">{{ $application->phone }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">الرقم القومي</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->national_id }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">المحافظة</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ Application::GOVERNORATES[$application->governorate] ?? ($application->governorate ?: '—') }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">المنطقة / الحي</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->residence_region ?: '—' }}</dd>
                        </div>
                        <div class="{{ $fieldBox }} sm:col-span-2">
                            <dt class="text-[11px] font-semibold text-slate-500">العنوان</dt>
                            <dd class="mt-0.5 font-medium leading-relaxed text-slate-900">{{ $application->address }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="{{ $card }}">
                    <h2 class="{{ $sectionTitle }}">البيانات الأكاديمية</h2>
                    <dl class="grid gap-2.5 text-sm sm:grid-cols-2">
                        <div class="{{ $fieldBox }} sm:col-span-2">
                            <dt class="text-[11px] font-semibold text-slate-500">الجامعة</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->university?->name ?? '—' }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">سنة التخرج</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->graduationYear?->year ?? '—' }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">القسم</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->department?->name ?? '—' }}</dd>
                        </div>
                        <div class="{{ $fieldBox }} sm:col-span-2">
                            <dt class="text-[11px] font-semibold text-slate-500">التخصص</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->specialization?->name ?? '—' }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">التقدير</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ Application::gradeLabels()[$application->grade] ?? $application->grade }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">المعدل</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ $application->gpa ?? '—' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="{{ $card }}">
                    <h2 class="{{ $sectionTitle }}">المهارات والعمل والتجنيد</h2>
                    <dl class="grid gap-2.5 text-sm sm:grid-cols-2">
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">حالة العمل</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ Application::employmentStatusLabels()[$application->employment_status] ?? '—' }}</dd>
                        </div>
                        <div class="{{ $fieldBox }}">
                            <dt class="text-[11px] font-semibold text-slate-500">موقوف من التجنيد</dt>
                            <dd class="mt-0.5 font-medium text-slate-900">{{ Application::militaryExemptionLabel($application->exempt_from_military) }}</dd>
                        </div>
                        <div class="{{ $fieldBox }} sm:col-span-2">
                            <dt class="text-[11px] font-semibold text-slate-500">المهارات</dt>
                            <dd class="mt-1 whitespace-pre-wrap font-medium leading-relaxed text-slate-900">{{ $application->skills ?: '—' }}</dd>
                        </div>
                        <div class="{{ $fieldBox }} sm:col-span-2">
                            <dt class="text-[11px] font-semibold text-slate-500">شهادات ودورات (نص)</dt>
                            <dd class="mt-1 whitespace-pre-wrap font-medium leading-relaxed text-slate-900">{{ $application->certificates_text ?: '—' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="{{ $card }}">
                    <div class="mb-4 flex flex-col gap-1 border-b border-white/50 pb-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">المرفقات</h2>
                            <p class="mt-0.5 text-xs text-slate-500">ملفات PDF المطلوبة مع الطلب — تُفتح في تبويب جديد.</p>
                        </div>
                        @if ($application->cv_path || $application->cert_path)
                            <span class="shrink-0 rounded-full bg-slate-100/80 px-2.5 py-1 text-[11px] font-semibold text-slate-600 ring-1 ring-white/60">
                                {{ collect([$application->cv_path, $application->cert_path])->filter()->count() }} ملف
                            </span>
                        @endif
                    </div>

                    @if (! $application->cv_path && ! $application->cert_path)
                        <div class="flex items-center gap-3 rounded-xl border border-dashed border-slate-200/90 bg-slate-50/50 px-4 py-6 text-sm text-slate-500">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-slate-400 shadow-sm ring-1 ring-slate-100" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75H12m-3.75-3h3.75m-3.75-3h3.75m6-3h.008v.008H18V9.75Zm-2.25 3h.008v.008H15.75V12.75Zm0 3h.008v.008H15.75V15.75Z" /></svg>
                            </span>
                            <span>لا توجد ملفات PDF مرفقة مع هذا الطلب.</span>
                        </div>
                    @else
                        <ul class="grid gap-3 sm:grid-cols-2" role="list">
                            @if ($application->cv_path)
                                <li>
                                    <a
                                        href="{{ asset('storage/'.$application->cv_path) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="group flex gap-3 rounded-xl border border-white/60 bg-gradient-to-br from-white/55 to-emerald-50/30 p-4 shadow-sm ring-1 ring-emerald-900/5 transition hover:border-emerald-200/80 hover:shadow-md hover:ring-emerald-200/40"
                                    >
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm ring-1 ring-emerald-700/20" aria-hidden="true">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-sm font-bold text-slate-900 group-hover:text-emerald-900">السيرة الذاتية</span>
                                            <span class="mt-0.5 block text-xs text-slate-500">PDF — عرض أو تنزيل من المتصفح</span>
                                            <span class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-700">
                                                فتح الملف
                                                <svg class="h-3.5 w-3.5 transition group-hover:-translate-x-0.5 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 15.75 12l-7.5 7.5" /></svg>
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ($application->cert_path)
                                <li>
                                    <a
                                        href="{{ asset('storage/'.$application->cert_path) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="group flex gap-3 rounded-xl border border-white/60 bg-gradient-to-br from-white/55 to-sky-50/30 p-4 shadow-sm ring-1 ring-sky-900/5 transition hover:border-sky-200/80 hover:shadow-md hover:ring-sky-200/40"
                                    >
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-600 text-white shadow-sm ring-1 ring-sky-700/20" aria-hidden="true">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm6 0a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm6 0a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" /></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-sm font-bold text-slate-900 group-hover:text-sky-900">شهادة التخرج</span>
                                            <span class="mt-0.5 block text-xs text-slate-500">PDF — عرض أو تنزيل من المتصفح</span>
                                            <span class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-sky-700">
                                                فتح الملف
                                                <svg class="h-3.5 w-3.5 transition group-hover:-translate-x-0.5 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 15.75 12l-7.5 7.5" /></svg>
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </section>

                @if ($application->isPending())
                    <div class="rounded-2xl border border-amber-200/70 bg-amber-50/60 p-5 shadow-md backdrop-blur-xl sm:p-6">
                        <p class="mb-4 text-sm font-semibold text-amber-950">إجراءات المراجعة</p>
                        <div class="flex flex-wrap gap-3">
                            <form method="POST" action="{{ route('admin.applications.approve', $application) }}">
                                @csrf
                                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">موافقة على الطلب</button>
                            </form>
                            <form method="POST" action="{{ route('admin.applications.reject', $application) }}" onsubmit="return confirm('تأكيد رفض الطلب؟');">
                                @csrf
                                <button type="submit" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-rose-700">رفض الطلب</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
