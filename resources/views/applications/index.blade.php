@extends('layouts.admin')

@section('title', 'طلبات التسجيل')

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">طلبات قيد المراجعة</h1>
            <p class="mt-1 text-sm text-slate-600">الطلبات ذات الحالة «معلّقة» فقط.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-white/50 bg-white/45 shadow-xl shadow-emerald-900/5 backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/50 text-sm">
                <thead class="bg-white/50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur-md">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">البريد</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">الجامعة</th>
                        <th class="px-4 py-3">القسم</th>
                        <th class="px-4 py-3">التخصص</th>
                        <th class="px-4 py-3">سنة التخرج</th>
                        <th class="px-4 py-3">التقدير</th>
                        <th class="px-4 py-3">العمل</th>
                        <th class="px-4 py-3">التجنيد</th>
                        <th class="px-4 py-3">المرفقات</th>
                        <th class="px-4 py-3 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40 bg-white/30">
                    @forelse ($applications as $application)
                        <tr class="hover:bg-white/40">
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-900">{{ $application->name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $application->email }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $application->phone }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $application->university?->name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $application->department?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $application->specialization?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $application->graduationYear?->year ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ \App\Models\Application::gradeLabels()[$application->grade] ?? $application->grade }}</td>
                            <td class="max-w-[140px] px-4 py-3 text-slate-700">{{ \App\Models\Application::employmentStatusLabels()[$application->employment_status] ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ \App\Models\Application::militaryExemptionLabel($application->exempt_from_military) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @if ($application->cv_path)
                                        <a href="{{ asset('storage/'.$application->cv_path) }}" target="_blank" class="rounded-lg bg-emerald-600/90 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-700">السيرة</a>
                                    @endif
                                    @if ($application->cert_path)
                                        <a href="{{ asset('storage/'.$application->cert_path) }}" target="_blank" class="rounded-lg bg-sky-600/90 px-2 py-1 text-xs font-semibold text-white hover:bg-sky-700">الشهادة</a>
                                    @endif
                                    @if ($application->photo_path)
                                        <a href="{{ asset('storage/'.$application->photo_path) }}" target="_blank" class="rounded-lg bg-stone-600/90 px-2 py-1 text-xs font-semibold text-white hover:bg-stone-700">الصورة</a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('admin.applications.show', $application) }}" class="rounded-lg border border-white/60 bg-white/70 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm hover:bg-white">تفاصيل</a>
                                    <form method="POST" action="{{ route('admin.applications.approve', $application) }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700">موافقة</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.applications.reject', $application) }}" onsubmit="return confirm('تأكيد رفض الطلب؟');">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-rose-700">رفض</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @if ($application->skills || $application->certificates_text)
                            <tr class="bg-emerald-50/40 text-xs text-slate-600">
                                <td colspan="12" class="px-4 py-2">
                                    @if ($application->skills)
                                        <p><span class="font-semibold text-slate-700">المهارات:</span> {{ $application->skills }}</p>
                                    @endif
                                    @if ($application->certificates_text)
                                        <p class="mt-1"><span class="font-semibold text-slate-700">شهادات ودورات:</span> {{ $application->certificates_text }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-10 text-center text-slate-600">لا توجد طلبات معلّقة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($applications->hasPages())
            <div class="border-t border-white/50 bg-white/40 px-4 py-3 backdrop-blur-md">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
@endsection
