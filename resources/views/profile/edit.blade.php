@php
    use App\Models\Application;
@endphp
@extends('layouts.student')

@section('title', 'ملف الخريج')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">ملفك</h1>
        <p class="mt-1 text-sm text-slate-600">يمكنك هنا تحديث <strong>البريد الإلكتروني</strong> و<strong>كلمة المرور</strong> و<strong>رقم الهاتف</strong> و<strong>العنوان</strong> و<strong>حالة العمل</strong> و<strong>موقفك من التجنيد</strong>، وما عداها من بيانات إن احتجت.</p>
    </div>

    <div class="space-y-8">
        <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg backdrop-blur-xl">
            <h2 class="text-lg font-semibold text-slate-900">بيانات أكاديمية (للاطلاع فقط)</h2>
            <p class="mt-1 text-xs text-slate-500">لتصحيح أخطاء، راجع الإدارة.</p>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-slate-500">الرقم القومي</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->national_id }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">الجامعة</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->university_name }}</dd>
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
                    <dd class="text-sm text-slate-900">{{ Application::gradeLabels()[$profile->grade] ?? $profile->grade }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">المعدل</dt>
                    <dd class="text-sm text-slate-900">{{ $profile->gpa !== null ? number_format((float) $profile->gpa, 2) : '—' }}</dd>
                </div>
            </dl>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="rounded-3xl border-2 border-emerald-200/60 bg-gradient-to-br from-emerald-50/80 to-white/50 p-6 shadow-lg backdrop-blur-xl">
                <h2 class="text-lg font-semibold text-emerald-900">ما يهمك تحديثه</h2>
                <p class="mt-1 text-sm text-slate-600">الهاتف، العنوان، حالة العمل، وموقف التجنيد.</p>
                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-800">رقم الهاتف</label>
                        <input name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full rounded-xl border border-white/60 bg-white/80 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-800">العنوان بالتفصيل</label>
                        <textarea name="address" rows="3" required placeholder="المركز / الشارع / المدينة…" class="w-full rounded-xl border border-white/60 bg-white/80 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">{{ old('address', $profile->address) }}</textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-800">حالة العمل حالياً</label>
                        <select name="employment_status" required class="w-full rounded-xl border border-white/60 bg-white/80 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                            <option value="">— اختر —</option>
                            @foreach (Application::employmentStatusLabels() as $value => $label)
                                <option value="{{ $value }}" @selected(old('employment_status', $profile->employment_status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-800">موقوف من التجنيد؟</label>
                        <select name="exempt_from_military" required class="w-full rounded-xl border border-white/60 bg-white/80 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                            <option value="1" @selected((string) old('exempt_from_military', $profile->exempt_from_military ? '1' : '0') === '1')>نعم</option>
                            <option value="0" @selected((string) old('exempt_from_military', $profile->exempt_from_military ? '1' : '0') === '0')>لا</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg backdrop-blur-xl">
                <h2 class="text-lg font-semibold text-slate-900">الاسم والبريد والمحافظة</h2>
                <p class="mt-1 text-sm text-slate-600">عدّل البريد هنا لاستخدامه في تسجيل الدخول لاحقاً. إن وُجد مستخدم آخر بنفس البريد لن يُقبل التعديل.</p>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">الاسم الكامل</label>
                        <input name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">المحافظة المقيم بها</label>
                        <select name="governorate" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">
                            <option value="">— اختر المحافظة —</option>
                            @foreach (Application::governorateLabels() as $value => $label)
                                <option value="{{ $value }}" @selected(old('governorate', $profile->governorate) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">المنطقة / الحي (مكان الإقامة)</label>
                        <input name="residence_region" value="{{ old('residence_region', $profile->residence_region) }}" required placeholder="الحي، المركز، الإدارة…" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-slate-50/80 p-6 shadow-lg backdrop-blur-xl">
                <h2 class="text-lg font-semibold text-slate-900">أمان الحساب — تغيير كلمة المرور</h2>
                <p class="mt-1 text-sm text-slate-600">اترك الحقول الثلاثة أدناه فارغة إن لم ترغب بتغيير كلمة المرور. عند التغيير: أدخل كلمة المرور الحالية ثم الجديدة مرتين.</p>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">كلمة المرور الحالية <span class="text-slate-500 font-normal">(مطلوبة فقط عند تعيين كلمة جديدة)</span></label>
                        <input type="password" name="current_password" autocomplete="current-password" class="w-full rounded-xl border border-white/60 bg-white px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                        @error('current_password')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">كلمة مرور جديدة</label>
                        <input type="password" name="password" autocomplete="new-password" class="w-full rounded-xl border border-white/60 bg-white px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                        @error('password')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password" class="w-full rounded-xl border border-white/60 bg-white px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg backdrop-blur-xl">
                <h2 class="text-lg font-semibold text-slate-900">المهارات والشهادات</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">المهارات</label>
                        <textarea name="skills" rows="3" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">{{ old('skills', $profile->skills) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">شهادات ودورات تدريبية (نص)</label>
                        <textarea name="certificates_text" rows="3" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm shadow-inner backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80">{{ old('certificates_text', $profile->certificates_text) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/50 bg-white/45 p-6 shadow-lg backdrop-blur-xl">
                <h2 class="text-lg font-semibold text-slate-900">المستندات</h2>
                <p class="mt-1 text-sm text-slate-600">اترك الحقل فارغاً إن لم ترغب بتغيير الملف. الملفات الحالية:</p>
                <ul class="mt-2 space-y-1 text-sm text-emerald-800">
                    <li>السيرة الذاتية: @if($profile->cv_path)<a href="{{ asset('storage/'.ltrim($profile->cv_path, '/')) }}" target="_blank" rel="noopener" class="font-medium underline">تحميل / عرض</a>@else<span class="text-slate-500">—</span>@endif</li>
                    <li>الشهادة: @if($profile->cert_path)<a href="{{ asset('storage/'.ltrim($profile->cert_path, '/')) }}" target="_blank" rel="noopener" class="font-medium underline">تحميل / عرض</a>@else<span class="text-slate-500">—</span>@endif</li>
                    <li>الصورة: @if($profile->photo_path)<a href="{{ asset('storage/'.ltrim($profile->photo_path, '/')) }}" target="_blank" rel="noopener" class="font-medium underline">تحميل / عرض</a>@else<span class="text-slate-500">—</span>@endif</li>
                </ul>
                <div class="mt-4 grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">سيرة ذاتية جديدة (PDF)</label>
                        <input type="file" name="cv" accept="application/pdf" class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-emerald-600/90 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-emerald-700" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">شهادة جديدة (PDF)</label>
                        <input type="file" name="cert" accept="application/pdf" class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-sky-600/90 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-sky-700" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">صورة شخصية جديدة</label>
                        <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-stone-600/90 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-stone-700" />
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-700/25 transition hover:bg-emerald-700">حفظ التعديلات</button>
            </div>
        </form>
    </div>
@endsection
