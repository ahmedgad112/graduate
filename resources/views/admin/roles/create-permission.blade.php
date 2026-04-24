@extends('layouts.admin')

@section('title', 'إنشاء صلاحية')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">إنشاء صلاحية جديدة</h1>
        <a href="{{ route('admin.roles.index') }}" class="mt-2 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">← العودة للقائمة</a>
    </div>

    <div class="max-w-xl rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
        <p class="mb-4 text-sm text-slate-600">الصلاحية اسم تقني يُستخدم في الكود والسياسات، مثل <code class="rounded bg-white/60 px-1">dashboard.view</code>. بعد الإنشاء تُضاف تلقائياً إلى <strong>دور المدير</strong>؛ يمكنك ربطها بأدوار أخرى من «تعديل الصلاحيات».</p>
        <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">اسم الصلاحية (بالإنجليزية)</label>
                <input name="name" value="{{ old('name') }}" required dir="ltr" placeholder="مثال: custom.report" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">إنشاء الصلاحية</button>
        </form>
    </div>
@endsection
