@extends('layouts.admin')

@section('title', 'الطلاب حسب سنة التخرج')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">الطلاب حسب سنة التخرج</h1>
        <p class="mt-1 text-sm text-slate-600">صفّي الخريجين حسب سنة التخرج، والقسم، والتخصص، والمحافظة (يمكنك دمج أكثر من معيار).</p>
    </div>

    <div class="mb-6 rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl shadow-emerald-900/5 backdrop-blur-xl">
        <form method="GET" action="{{ route('admin.students.by-year') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">سنة التخرج</label>
                <select name="graduation_year_id" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                    <option value="">— الكل / لا يهم —</option>
                    @foreach ($graduationYears as $gy)
                        <option value="{{ $gy->id }}" @selected((string) request('graduation_year_id') === (string) $gy->id)>{{ $gy->year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">القسم</label>
                <select
                    name="department_id"
                    id="department_id"
                    class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80"
                >
                    <option value="">— الكل / لا يهم —</option>
                    @foreach ($departmentsJson as $d)
                        <option value="{{ $d['id'] }}" @selected((string) request('department_id') === (string) $d['id'])>{{ $d['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">التخصص</label>
                <select
                    name="specialization_id"
                    id="specialization_id"
                    class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80"
                >
                    <option value="">— اختر القسم أولاً —</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">المحافظة</label>
                <select
                    name="governorate"
                    class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80"
                >
                    <option value="">— الكل / لا يهم —</option>
                    @foreach ($governorates as $key => $label)
                        <option value="{{ $key }}" @selected(request('governorate') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-3 xl:col-span-4">
                <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">عرض الطلاب</button>
                @if (request()->filled('graduation_year_id') || request()->filled('department_id') || request()->filled('specialization_id') || request()->filled('governorate'))
                    <a
                        href="{{ route('admin.students.by-year') }}"
                        class="rounded-xl border border-white/60 bg-white/50 px-5 py-2 text-sm font-semibold text-slate-800 backdrop-blur hover:bg-white/70"
                    >مسح</a>
                @endif
            </div>
        </form>
        @if ($graduationYears->isEmpty())
            <p class="mt-4 text-sm text-amber-800">لم تُعرّف سنوات تخرج بعد. أضف سنوات من «سنوات التخرج» في القائمة.</p>
        @endif
    </div>

    @if ($students !== null)
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <p class="text-sm font-medium text-slate-700">
                @if ($students->total() > 0)
                    عدد النتائج: <span class="font-bold text-slate-900">{{ $students->total() }}</span> خريج
                @else
                    لا توجد نتائج مطابقة لمعايير البحث الحالية.
                @endif
            </p>
        </div>

        <div
            class="rounded-2xl border border-white/50 bg-gradient-to-b from-white/30 to-white/45 p-3 shadow-lg shadow-emerald-900/5 backdrop-blur-xl sm:p-4"
        >
            @if ($students->isEmpty())
                <div class="rounded-xl border border-dashed border-white/60 bg-white/30 px-4 py-10 text-center text-sm text-slate-600">
                    لا توجد نتائج.
                </div>
            @else
                <div class="space-y-2.5">
                    @foreach ($students as $profile)
                        @php($user = $profile->user)
                        @php($i = $students->firstItem() + $loop->index)
                        <article
                            class="overflow-hidden rounded-xl border border-slate-200/60 bg-white/70 shadow-sm transition hover:border-emerald-300/50 hover:shadow"
                        >
                            <div class="p-3 sm:p-3.5">
                                <div
                                    class="mb-2.5 flex flex-row flex-wrap gap-2 border-b border-slate-200/50 pb-2.5 sm:items-center sm:justify-between"
                                >
                                    <div class="flex min-w-0 items-center gap-2">
                                        <div
                                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100/90 text-xs font-bold text-emerald-800"
                                            aria-hidden="true"
                                        >{{ $i }}</div>
                                        <h2 class="min-w-0 text-base font-bold leading-tight text-slate-900">
                                            {{ $user?->name ?? '—' }}
                                        </h2>
                                    </div>
                                    <a
                                        href="{{ route('admin.profiles.show', $profile) }}"
                                        class="inline-flex w-fit shrink-0 items-center justify-center gap-1 rounded-lg bg-emerald-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        عرض الملف
                                    </a>
                                </div>

                                <div>
                                    <h3 class="mb-1.5 text-[0.65rem] font-bold uppercase tracking-wider text-slate-400">المعلومات الأساسية</h3>
                                    <div
                                        class="grid grid-cols-1 gap-x-3 gap-y-1.5 sm:grid-cols-2 lg:grid-cols-3"
                                    >
                                        <div>
                                            <p class="text-[0.65rem] font-medium text-slate-500">البريد</p>
                                            <p class="break-all text-xs leading-tight text-slate-800">{{ $user?->email ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-medium text-slate-500">الهاتف</p>
                                            <p class="text-right text-xs leading-tight text-slate-800" dir="ltr">{{ $user?->phone ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-medium text-slate-500">الرقم القومي</p>
                                            <p class="text-right text-xs leading-tight text-slate-800" dir="ltr">{{ $profile->national_id ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-medium text-slate-500">الجامعة</p>
                                            <p class="text-xs leading-tight text-slate-800">{{ $profile->university_name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-medium text-slate-500">القسم</p>
                                            <p class="text-xs leading-tight text-slate-800">{{ $profile->department?->name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-medium text-slate-500">التخصص</p>
                                            <p class="text-xs leading-tight text-slate-800">{{ $profile->specialization?->name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-medium text-slate-500">سنة التخرج</p>
                                            <p class="text-xs leading-tight text-slate-800">{{ $profile->graduationYear?->year ?? '—' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
            @if ($students->hasPages())
                <div class="mt-4 border-t border-white/50 bg-white/30 px-2 py-3 backdrop-blur-sm sm:px-4">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    @elseif ($graduationYears->isNotEmpty())
        <div class="rounded-3xl border border-white/50 bg-white/40 px-6 py-10 text-center text-slate-600 backdrop-blur-xl">
            اختر معياراً واحداً على الأقل (سنة تخرج، قسم، تخصص، أو محافظة) ثم اضغط «عرض الطلاب».
        </div>
    @endif
    <script>
        (function () {
            const departmentsData = @json($departmentsJson);
            const oldDeptId = @json(request('department_id'));
            const oldSpecId = @json(request('specialization_id'));
            const deptSel = document.getElementById('department_id');
            const specSel = document.getElementById('specialization_id');
            if (!deptSel || !specSel) {
                return;
            }
            function syncSpecializations(applyOldSpec) {
                const id = deptSel.value;
                specSel.innerHTML = '<option value="">— الكل / لا يهم —</option>';
                if (!id) {
                    specSel.innerHTML = '<option value="">— اختر القسم أولاً —</option>';
                    specSel.disabled = true;
                    return;
                }
                const dept = departmentsData.find(function (d) { return String(d.id) === String(id); });
                if (!dept || !dept.specializations.length) {
                    specSel.innerHTML = '<option value="">— لا توجد تخصصات —</option>';
                    specSel.disabled = true;
                    return;
                }
                dept.specializations.forEach(function (s) {
                    const o = document.createElement('option');
                    o.value = s.id;
                    o.textContent = s.name;
                    if (applyOldSpec && oldSpecId != null && String(s.id) === String(oldSpecId)) {
                        o.selected = true;
                    }
                    specSel.appendChild(o);
                });
                specSel.disabled = false;
            }
            deptSel.addEventListener('change', function () {
                syncSpecializations(false);
            });
            if (oldDeptId) {
                deptSel.value = String(oldDeptId);
            }
            if (deptSel.value) {
                syncSpecializations(true);
            } else {
                specSel.innerHTML = '<option value="">— اختر القسم أولاً —</option>';
                specSel.disabled = true;
            }
        })();
    </script>
@endsection
