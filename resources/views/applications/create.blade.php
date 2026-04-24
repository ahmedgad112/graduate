@extends('layouts.app')

@section('title', 'تسجيل خريج جديد')

@section('content')
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(16,185,129,0.12),transparent_45%),radial-gradient(circle_at_80%_0%,rgba(14,165,233,0.12),transparent_40%),radial-gradient(circle_at_50%_80%,rgba(120,113,108,0.08),transparent_45%)]"></div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl flex-col gap-8 px-4 py-10 lg:flex-row lg:items-stretch lg:px-8">
            <section class="flex flex-1 flex-col justify-center lg:max-w-md">
                <div class="rounded-3xl border border-white/50 bg-white/40 p-8 shadow-xl shadow-emerald-900/5 backdrop-blur-xl">
                    <p class="text-sm font-medium text-emerald-700/90">بوابة الخريجين</p>
                    <h1 class="mt-2 text-3xl font-bold text-slate-900">سجّل بياناتك الأكاديمية</h1>
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">
                        املأ النموذج بعناية وارفع المستندات المطلوبة. بعد الإرسال ستقوم الإدارة بمراجعة طلبك وإنشاء ملفك الدائم عند الموافقة.
                    </p>
                    <p class="mt-3 text-sm text-slate-600">
                        لديك حساب خريج بالفعل؟
                        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 underline decoration-emerald-500/30 hover:text-emerald-800">تسجيل الدخول</a>
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-emerald-500/80"></span>
                            <span>تأكد من صحة البريد والهاتف؛ ستُستخدم لإنشاء حسابك.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-sky-500/80"></span>
                            <span>الملفات: السيرة الذاتية والشهادة بصيغة PDF، وصورة شخصية واضحة.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="flex flex-[1.35] items-center">
                <div class="w-full rounded-3xl border border-white/55 bg-white/45 p-6 shadow-2xl shadow-emerald-900/10 backdrop-blur-2xl md:p-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-emerald-200/60 bg-emerald-50/85 px-4 py-3 text-sm text-emerald-900 backdrop-blur-md">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-200/70 bg-rose-50/85 px-4 py-3 text-sm text-rose-900 backdrop-blur-md">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium text-slate-700">الاسم الكامل</label>
                                <input name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner shadow-white/40 backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">الهاتف</label>
                                <input name="phone" value="{{ old('phone') }}" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">الرقم القومي</label>
                                <input name="national_id" value="{{ old('national_id') }}" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">سنة التخرج</label>
                                <select name="graduation_year_id" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                                    <option value="">— اختر —</option>
                                    @foreach ($graduationYears as $gy)
                                        <option value="{{ $gy->id }}" @selected(old('graduation_year_id') == $gy->id)>{{ $gy->year }}</option>
                                    @endforeach
                                </select>
                                @if ($graduationYears->isEmpty())
                                    <p class="mt-1 text-xs text-amber-800">لا توجد سنوات تخرج مفعّلة بعد. تواصل مع الإدارة.</p>
                                @endif
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium text-slate-700">العنوان</label>
                                <textarea name="address" rows="2" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">{{ old('address') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium text-slate-700">المحافظة المقيم بها</label>
                                <select name="governorate" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                                    <option value="">— اختر المحافظة —</option>
                                    @foreach (\App\Models\Application::governorateLabels() as $value => $label)
                                        <option value="{{ $value }}" @selected(old('governorate') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">الجامعة</label>
                                <select name="university_id" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                                    <option value="">— اختر —</option>
                                    @foreach ($universities as $university)
                                        <option value="{{ $university->id }}" @selected(old('university_id') == $university->id)>{{ $university->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">القسم</label>
                                <select name="department_id" id="department_id" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                                    <option value="">— اختر —</option>
                                    @foreach ($departmentsJson as $d)
                                        <option value="{{ $d['id'] }}" @selected(old('department_id') == $d['id'])>{{ $d['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">التخصص</label>
                                <select name="specialization_id" id="specialization_id" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" disabled>
                                    <option value="">— اختر القسم أولاً —</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">التقدير</label>
                                <select name="grade" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                                    <option value="">— اختر —</option>
                                    @foreach (\App\Models\Application::gradeLabels() as $value => $label)
                                        <option value="{{ $value }}" @selected(old('grade') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">المعدل (اختياري)</label>
                                <input type="number" step="0.01" name="gpa" value="{{ old('gpa') }}" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/50 bg-white/35 p-4 backdrop-blur-md">
                            <p class="mb-3 text-sm font-semibold text-slate-800">المهارات والشهادات وحالة العمل</p>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">المهارات</label>
                                    <textarea name="skills" rows="3" placeholder="اذكر مهاراتك المهنية والتقنية…" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">{{ old('skills') }}</textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">شهادات ودورات تدريبية إضافية (نص)</label>
                                    <textarea name="certificates_text" rows="3" placeholder="شهادات أو دورات غير المرفوعة كملف أعلاه…" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">{{ old('certificates_text') }}</textarea>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">حالة العمل حالياً</label>
                                    <select name="employment_status" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                                        <option value="">— اختر —</option>
                                        @foreach (\App\Models\Application::employmentStatusLabels() as $value => $label)
                                            <option value="{{ $value }}" @selected(old('employment_status') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">موقوف من التجنيد؟</label>
                                    <select name="exempt_from_military" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                                        <option value="">— اختر —</option>
                                        <option value="1" @selected((string) old('exempt_from_military') === '1')>نعم</option>
                                        <option value="0" @selected((string) old('exempt_from_military') === '0')>لا</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">السيرة الذاتية (PDF)</label>
                                <input type="file" name="cv" accept="application/pdf" required class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-emerald-600/90 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-emerald-700" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">الشهادة (PDF)</label>
                                <input type="file" name="cert" accept="application/pdf" required class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-sky-600/90 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-sky-700" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">الصورة الشخصية</label>
                                <input type="file" name="photo" accept="image/*" required class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-stone-600/90 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-stone-700" />
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-slate-500">بإرسالك للنموذج، تؤكد صحة البيانات المدخلة.</p>
                            <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-700">إرسال الطلب</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <script>
        (function () {
            const departmentsData = @json($departmentsJson);
            const oldDeptId = @json(old('department_id'));
            const oldSpecId = @json(old('specialization_id'));
            const deptSel = document.getElementById('department_id');
            const specSel = document.getElementById('specialization_id');
            function syncSpecializations() {
                const id = deptSel.value;
                specSel.innerHTML = '<option value="">— اختر —</option>';
                if (!id) {
                    specSel.disabled = true;
                    return;
                }
                const dept = departmentsData.find(function (d) { return String(d.id) === String(id); });
                if (!dept || !dept.specializations.length) {
                    specSel.disabled = true;
                    return;
                }
                dept.specializations.forEach(function (s) {
                    const o = document.createElement('option');
                    o.value = s.id;
                    o.textContent = s.name;
                    if (oldSpecId != null && String(s.id) === String(oldSpecId)) {
                        o.selected = true;
                    }
                    specSel.appendChild(o);
                });
                specSel.disabled = false;
            }
            deptSel.addEventListener('change', syncSpecializations);
            if (oldDeptId) {
                deptSel.value = String(oldDeptId);
            }
            if (deptSel.value) {
                syncSpecializations();
            }
        })();
    </script>
@endsection
