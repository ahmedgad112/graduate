@extends('layouts.admin')

@section('title', 'الطلاب حسب سنة التخرج')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">الطلاب حسب سنة التخرج</h1>
        <p class="mt-1 text-sm text-slate-600">اختر سنة التخرج المعرفة من الإدارة لعرض جميع الخريجين المسجلين تحتها.</p>
    </div>

    <div class="mb-6 rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl shadow-emerald-900/5 backdrop-blur-xl">
        <form method="GET" action="{{ route('admin.students.by-year') }}" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px] flex-1">
                <label class="mb-1 block text-sm font-medium text-slate-700">سنة التخرج</label>
                <select name="graduation_year_id" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" required>
                    <option value="">— اختر السنة —</option>
                    @foreach ($graduationYears as $gy)
                        <option value="{{ $gy->id }}" @selected((string) $selectedGraduationYearId === (string) $gy->id)>{{ $gy->year }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">عرض الطلاب</button>
            @if ($selectedGraduationYearId !== null && $selectedGraduationYearId !== '')
                <a href="{{ route('admin.students.by-year') }}" class="rounded-xl border border-white/60 bg-white/50 px-5 py-2 text-sm font-semibold text-slate-800 backdrop-blur hover:bg-white/70">مسح</a>
            @endif
        </form>
        @if ($graduationYears->isEmpty())
            <p class="mt-4 text-sm text-amber-800">لم تُعرّف سنوات تخرج بعد. أضف سنوات من «سنوات التخرج» في القائمة.</p>
        @endif
    </div>

    @if ($students !== null && $selectedGraduationYear)
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <p class="text-sm font-medium text-slate-700">
                @if ($students->total() > 0)
                    عدد الطلاب لعام <span class="font-bold text-slate-900">{{ $selectedGraduationYear->year }}</span>: {{ $students->total() }}
                @else
                    لا يوجد طلاب مسجلون لعام <span class="font-bold">{{ $selectedGraduationYear->year }}</span>.
                @endif
            </p>
        </div>

        <div class="overflow-hidden rounded-3xl border border-white/50 bg-white/45 shadow-xl shadow-emerald-900/5 backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/50 text-sm">
                    <thead class="bg-white/50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur-md">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">الاسم</th>
                            <th class="px-4 py-3">البريد</th>
                            <th class="px-4 py-3">الهاتف</th>
                            <th class="px-4 py-3">الرقم القومي</th>
                            <th class="px-4 py-3">الجامعة</th>
                            <th class="px-4 py-3">القسم</th>
                            <th class="px-4 py-3">التخصص</th>
                            <th class="px-4 py-3">التقدير</th>
                            <th class="px-4 py-3">المعدل</th>
                            <th class="px-4 py-3">العمل</th>
                            <th class="px-4 py-3">التجنيد</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/40 bg-white/30">
                        @forelse ($students as $profile)
                            @php($user = $profile->user)
                            <tr class="hover:bg-white/40">
                                <td class="px-4 py-3 text-slate-500">{{ $profile->id }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-900">{{ $user?->name }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $user?->email }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $user?->phone }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $profile->national_id }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $profile->university_name }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $profile->department?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $profile->specialization?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ \App\Models\Application::gradeLabels()[$profile->grade] ?? $profile->grade }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $profile->gpa ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ \App\Models\Application::employmentStatusLabels()[$profile->employment_status] ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ \App\Models\Application::militaryExemptionLabel($profile->exempt_from_military) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-10 text-center text-slate-600">لا توجد نتائج.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($students->hasPages())
                <div class="border-t border-white/50 bg-white/40 px-4 py-3 backdrop-blur-md">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    @elseif ($graduationYears->isNotEmpty())
        <div class="rounded-3xl border border-white/50 bg-white/40 px-6 py-10 text-center text-slate-600 backdrop-blur-xl">
            اختر سنة التخرج من القائمة أعلاه ثم اضغط «عرض الطلاب».
        </div>
    @endif
@endsection
