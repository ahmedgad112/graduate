@extends('layouts.admin')

@php use App\Authorization\Permissions; @endphp

@section('title', 'لوحة التحكم')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">لوحة التحكم</h1>
        <p class="mt-1 text-sm text-slate-600">إحصائيات سريعة وقائمة الخريجين المعتمدين مع تصفية متقدمة وتصدير Excel.</p>
    </div>

    <div class="mb-8 grid gap-4 md:grid-cols-2">
        <div class="rounded-3xl border border-white/50 bg-gradient-to-br from-emerald-500/15 to-white/50 p-6 shadow-lg shadow-emerald-900/10 backdrop-blur-xl">
            <p class="text-sm font-medium text-emerald-900/80">إجمالي الخريجين</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalGraduates }}</p>
        </div>
        <div class="rounded-3xl border border-white/50 bg-gradient-to-br from-amber-500/15 to-white/50 p-6 shadow-lg shadow-amber-900/10 backdrop-blur-xl">
            <p class="text-sm font-medium text-amber-900/80">طلبات قيد المراجعة</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingApps }}</p>
        </div>
    </div>

    <div class="mb-6 rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl shadow-emerald-900/5 backdrop-blur-xl">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-600">سنة التخرج</label>
                <select name="graduation_year_id" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                    <option value="">الكل</option>
                    @foreach ($graduationYears as $gy)
                        <option value="{{ $gy->id }}" @selected(request('graduation_year_id') == $gy->id)>{{ $gy->year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-600">التقدير</label>
                <select name="grade" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                    <option value="">الكل</option>
                    @foreach ($grades as $g)
                        <option value="{{ $g }}" @selected(request('grade') === $g)>{{ \App\Models\Application::gradeLabels()[$g] ?? $g }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-600">الجامعة</label>
                <select name="university_id" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                    <option value="">الكل</option>
                    @foreach ($universities as $university)
                        <option value="{{ $university->id }}" @selected(request('university_id') == $university->id)>{{ $university->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-600">القسم</label>
                <select name="department_id" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                    <option value="">الكل</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 lg:col-span-4 flex flex-wrap gap-2">
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">تطبيق التصفية</button>
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-white/60 bg-white/50 px-5 py-2 text-sm font-semibold text-slate-800 backdrop-blur hover:bg-white/70">إعادة ضبط</a>
                @can(Permissions::GRADUATES_EXPORT)
                    <a href="{{ route('admin.graduates.export', request()->query()) }}" class="rounded-xl bg-slate-800 px-5 py-2 text-sm font-semibold text-white shadow-md hover:bg-slate-900">تصدير Excel للنتائج الحالية</a>
                @endcan
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl border border-white/50 bg-white/45 shadow-xl shadow-emerald-900/5 backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/50 text-sm">
                <thead class="bg-white/50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur-md">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">البريد</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">المحافظة</th>
                        <th class="px-4 py-3">المنطقة</th>
                        <th class="px-4 py-3">الجامعة</th>
                        <th class="px-4 py-3">القسم</th>
                        <th class="px-4 py-3">التخصص</th>
                        <th class="px-4 py-3">سنة التخرج</th>
                        <th class="px-4 py-3">التقدير</th>
                        <th class="px-4 py-3">المعدل</th>
                        <th class="px-4 py-3">حالة العمل</th>
                        <th class="px-4 py-3">التجنيد</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40 bg-white/30">
                    @forelse ($graduates as $profile)
                        @php($user = $profile->user)
                        <tr class="hover:bg-white/40">
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-900">{{ $user?->name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $user?->email }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $user?->phone }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $profile->governorate ? (\App\Models\Application::GOVERNORATES[$profile->governorate] ?? $profile->governorate) : '—' }}</td>
                            <td class="max-w-[140px] px-4 py-3 text-slate-700">{{ $profile->residence_region ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $profile->university_name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $profile->department?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $profile->specialization?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $profile->graduationYear?->year ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ \App\Models\Application::gradeLabels()[$profile->grade] ?? $profile->grade }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $profile->gpa ?? '—' }}</td>
                            <td class="max-w-[120px] px-4 py-3 text-slate-700">{{ \App\Models\Application::employmentStatusLabels()[$profile->employment_status] ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ \App\Models\Application::militaryExemptionLabel($profile->exempt_from_military) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-10 text-center text-slate-600">لا يوجد خريجون مطابقون للتصفية.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($graduates->hasPages())
            <div class="border-t border-white/50 bg-white/40 px-4 py-3 backdrop-blur-md">
                {{ $graduates->links() }}
            </div>
        @endif
    </div>
@endsection
