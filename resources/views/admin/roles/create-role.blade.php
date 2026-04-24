@extends('layouts.admin')

@section('title', 'إنشاء دور')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">إنشاء دور جديد</h1>
        <a href="{{ route('admin.roles.index') }}" class="mt-2 inline-block text-sm font-medium text-emerald-700 hover:text-emerald-800">← العودة للقائمة</a>
    </div>

    <div class="max-w-xl rounded-3xl border border-white/50 bg-white/45 p-6 shadow-xl backdrop-blur-xl">
        <p class="mb-4 text-sm text-slate-600">استخدم اسماً بالإنجليزية بصيغة مثل <code class="rounded bg-white/60 px-1">editor</code> أو <code class="rounded bg-white/60 px-1">data.entry</code> (حروف وأرقام ونقطة وشرطة فقط). بعد الإنشاء يمكنك من «تعديل الصلاحيات» ربط الصلاحيات بهذا الدور.</p>
        <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">معرّف الدور (بالإنجليزية)</label>
                <input name="name" value="{{ old('name') }}" required dir="ltr" placeholder="مثال: reports.viewer" class="w-full rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm backdrop-blur focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200/80" />
            </div>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">إنشاء الدور</button>
        </form>
    </div>
@endsection
