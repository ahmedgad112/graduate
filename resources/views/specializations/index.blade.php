@extends('layouts.admin')

@section('title', 'تخصصات القسم')

@section('content')
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">تخصصات: {{ $department->name }}</h1>
            <a href="{{ route('admin.departments.index') }}" class="mt-2 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">← كل الأقسام</a>
        </div>
        <a href="{{ route('admin.departments.specializations.create', $department) }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-700">إضافة تخصص</a>
    </div>

    @if ($errors->has('delete'))
        <div class="mb-4 rounded-2xl border border-rose-200/70 bg-rose-50/85 px-4 py-3 text-sm text-rose-900">{{ $errors->first('delete') }}</div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-white/50 bg-white/45 shadow-xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/50 text-sm">
                <thead class="bg-white/50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">اسم التخصص</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40 bg-white/30">
                    @forelse ($specializations as $specialization)
                        <tr class="hover:bg-white/40">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $specialization->name }}</td>
                            <td class="px-4 py-3">
                                @if ($specialization->is_active)
                                    <span class="rounded-full bg-emerald-100/80 px-2 py-1 text-xs font-semibold text-emerald-800">نشط</span>
                                @else
                                    <span class="rounded-full bg-stone-200/80 px-2 py-1 text-xs font-semibold text-stone-700">موقوف</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('admin.departments.specializations.edit', [$department, $specialization]) }}" class="rounded-lg border border-white/60 bg-white/60 px-3 py-1 text-xs font-semibold text-slate-800 hover:bg-white/80">تعديل</a>
                                    <form method="POST" action="{{ route('admin.departments.specializations.destroy', [$department, $specialization]) }}" onsubmit="return confirm('حذف هذا التخصص؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-600 px-3 py-1 text-xs font-semibold text-white hover:bg-rose-700">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-slate-600">لا توجد تخصصات بعد. أضف تخصصاً ليظهر في نموذج التسجيل.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($specializations->hasPages())
            <div class="border-t border-white/50 bg-white/40 px-4 py-3">
                {{ $specializations->links() }}
            </div>
        @endif
    </div>
@endsection
