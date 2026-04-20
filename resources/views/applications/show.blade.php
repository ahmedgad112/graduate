@extends('layouts.admin')

@section('title', 'طلب تسجيل: '.$application->name)

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.applications.index') }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">← العودة لقائمة الطلبات</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">تفاصيل طلب التسجيل</h1>
            <p class="mt-1 text-sm text-slate-600">عرض كامل لبيانات مقدّم الطلب والمرفقات.</p>
        </div>
        <div class="rounded-2xl border border-white/50 bg-white/50 px-4 py-2 text-sm backdrop-blur-md">
            <span class="text-slate-500">حالة الطلب:</span>
            <span class="mr-2 font-semibold text-slate-900">{{ \App\Models\Application::statusLabels()[$application->status] ?? $application->status }}</span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
                <h2 class="mb-4 text-sm font-semibold text-slate-800">الصورة الشخصية</h2>
                @if ($application->photo_path)
                    <div class="overflow-hidden rounded-2xl border border-white/60 bg-white/60 shadow-inner">
                        <img
                            src="{{ asset('storage/'.$application->photo_path) }}"
                            alt="صورة {{ $application->name }}"
                            class="mx-auto max-h-96 w-full object-contain object-top"
                        />
                    </div>
                    <a href="{{ asset('storage/'.$application->photo_path) }}" target="_blank" class="mt-3 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">فتح الصورة بحجم كامل</a>
                @else
                    <p class="text-sm text-slate-500">لا توجد صورة مرفوعة.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
                <h2 class="mb-4 border-b border-white/40 pb-2 text-sm font-semibold text-slate-800">البيانات الشخصية</h2>
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-slate-500">الاسم</dt><dd class="font-medium text-slate-900">{{ $application->name }}</dd></div>
                    <div><dt class="text-slate-500">البريد</dt><dd class="font-medium text-slate-900">{{ $application->email }}</dd></div>
                    <div><dt class="text-slate-500">الهاتف</dt><dd class="font-medium text-slate-900">{{ $application->phone }}</dd></div>
                    <div><dt class="text-slate-500">الرقم القومي</dt><dd class="font-medium text-slate-900">{{ $application->national_id }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-slate-500">العنوان</dt><dd class="font-medium text-slate-900">{{ $application->address }}</dd></div>
                </dl>
            </div>

            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
                <h2 class="mb-4 border-b border-white/40 pb-2 text-sm font-semibold text-slate-800">البيانات الأكاديمية</h2>
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-slate-500">الجامعة</dt><dd class="font-medium text-slate-900">{{ $application->university?->name ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">سنة التخرج</dt><dd class="font-medium text-slate-900">{{ $application->graduationYear?->year ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">القسم</dt><dd class="font-medium text-slate-900">{{ $application->department?->name ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">التخصص</dt><dd class="font-medium text-slate-900">{{ $application->specialization?->name ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">التقدير</dt><dd class="font-medium text-slate-900">{{ \App\Models\Application::gradeLabels()[$application->grade] ?? $application->grade }}</dd></div>
                    <div><dt class="text-slate-500">المعدل</dt><dd class="font-medium text-slate-900">{{ $application->gpa ?? '—' }}</dd></div>
                </dl>
            </div>

            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
                <h2 class="mb-4 border-b border-white/40 pb-2 text-sm font-semibold text-slate-800">المهارات والعمل والتجنيد</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-slate-500">حالة العمل</dt><dd class="font-medium text-slate-900">{{ \App\Models\Application::employmentStatusLabels()[$application->employment_status] ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">موقوف من التجنيد</dt><dd class="font-medium text-slate-900">{{ \App\Models\Application::militaryExemptionLabel($application->exempt_from_military) }}</dd></div>
                    <div>
                        <dt class="text-slate-500">المهارات</dt>
                        <dd class="mt-1 whitespace-pre-wrap font-medium text-slate-900">{{ $application->skills ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">شهادات ودورات (نص)</dt>
                        <dd class="mt-1 whitespace-pre-wrap font-medium text-slate-900">{{ $application->certificates_text ?: '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
                <h2 class="mb-4 border-b border-white/40 pb-2 text-sm font-semibold text-slate-800">المرفقات</h2>
                <div class="flex flex-wrap gap-3">
                    @if ($application->cv_path)
                        <a href="{{ asset('storage/'.$application->cv_path) }}" target="_blank" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">تحميل السيرة الذاتية (PDF)</a>
                    @endif
                    @if ($application->cert_path)
                        <a href="{{ asset('storage/'.$application->cert_path) }}" target="_blank" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">تحميل الشهادة (PDF)</a>
                    @endif
                </div>
            </div>

            @if ($application->isPending())
                <div class="flex flex-wrap gap-3 rounded-3xl border border-amber-200/60 bg-amber-50/50 p-6 backdrop-blur-xl">
                    <form method="POST" action="{{ route('admin.applications.approve', $application) }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">موافقة على الطلب</button>
                    </form>
                    <form method="POST" action="{{ route('admin.applications.reject', $application) }}" onsubmit="return confirm('تأكيد رفض الطلب؟');">
                        @csrf
                        <button type="submit" class="rounded-xl bg-rose-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-rose-700">رفض الطلب</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
