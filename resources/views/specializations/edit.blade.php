@extends('layouts.admin')

@section('title', 'تعديل تخصص')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">تعديل تخصص — {{ $department->name }}</h1>
        <a href="{{ route('admin.departments.specializations.index', $department) }}" class="mt-2 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">← قائمة التخصصات</a>
    </div>

    <div class="max-w-xl rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
        <form method="POST" action="{{ route('admin.departments.specializations.update', [$department, $specialization]) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">اسم التخصص</label>
                <input name="name" value="{{ old('name', $specialization->name) }}" required class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $specialization->is_active)) class="rounded border-white/60 text-emerald-600 focus:ring-emerald-500" />
                التخصص نشط ويظهر في نموذج التسجيل
            </label>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">تحديث</button>
        </form>
    </div>
@endsection
