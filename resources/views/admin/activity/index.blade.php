@extends('layouts.admin')

@section('title', 'سجل النشاط')

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">سجل النشاط</h1>
            <p class="mt-1 text-sm text-slate-600">تتبع التعديلات على البيانات حسب المستخدم والتاريخ (Spatie Activity Log).</p>
        </div>
        <form method="get" action="{{ route('admin.activity.index') }}" class="flex flex-wrap items-center gap-2">
            <label for="log" class="text-sm text-slate-600">تصفية حسب السجل</label>
            <select name="log" id="log" onchange="this.form.submit()"
                class="rounded-xl border border-white/60 bg-white/60 px-3 py-2 text-sm text-slate-800 shadow-sm backdrop-blur">
                <option value="">— الكل —</option>
                @foreach ($logNames as $name)
                    <option value="{{ $name }}" @selected($activeLog === $name)>{{ $name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl border border-white/50 bg-white/45 shadow-xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/50 text-sm">
                <thead class="bg-white/50 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">الوقت</th>
                        <th class="px-4 py-3">الوصف</th>
                        <th class="px-4 py-3">السجل</th>
                        <th class="px-4 py-3">الفاعل</th>
                        <th class="px-4 py-3">الكيان</th>
                        <th class="px-4 py-3 min-w-[12rem]">التفاصيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40 bg-white/30">
                    @forelse ($activities as $activity)
                        <tr class="align-top hover:bg-white/40">
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $activity->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $activity->description }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $activity->log_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                @if ($activity->causer)
                                    {{ $activity->causer->name ?? class_basename($activity->causer_type).' #'.$activity->causer_id }}
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                @if ($activity->subject)
                                    <span class="text-xs text-slate-500">{{ str_replace('App\\Models\\', '', (string) $activity->subject_type) }}</span>
                                    <span class="block font-medium text-slate-800">#{{ $activity->subject_id }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $changes = $activity->properties?->only(['attributes', 'old']) ?? collect();
                                @endphp
                                @if ($changes->isNotEmpty())
                                    <pre class="max-h-40 max-w-md overflow-auto rounded-lg bg-slate-900/90 p-2 text-left text-xs text-emerald-100/90" dir="ltr">{{ json_encode($changes->all(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">لا توجد سجلات بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($activities->hasPages())
            <div class="border-t border-white/50 bg-white/40 px-4 py-3">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
@endsection
