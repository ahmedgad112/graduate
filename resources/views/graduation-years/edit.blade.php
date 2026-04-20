@extends('layouts.admin')

@section('title', 'تعديل سنة تخرج')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">تعديل سنة تخرج</h1>
        <a href="{{ route('admin.graduation-years.index') }}" class="mt-2 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">← العودة للقائمة</a>
    </div>

    <div class="max-w-xl rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
        <form method="POST" action="{{ route('admin.graduation-years.update', $graduationYear) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">سنة التخرج (رقم)</label>
                <input type="number" name="year" value="{{ old('year', $graduationYear->year) }}" min="1970" max="2100" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $graduationYear->is_active)) class="rounded border-white/60 text-emerald-600 focus:ring-emerald-500" />
                السنة متاحة في نموذج تسجيل الخريجين
            </label>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">تحديث</button>
        </form>
    </div>
@endsection
