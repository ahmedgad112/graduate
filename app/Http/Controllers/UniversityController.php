<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UniversityController extends Controller
{
    public function index(): View
    {
        $universities = University::query()->orderBy('name')->paginate(15);

        return view('universities.index', compact('universities'));
    }

    public function create(): View
    {
        return view('universities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        University::query()->create([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.universities.index')
            ->with('status', __('تمت إضافة الجامعة.'));
    }

    public function edit(University $university): View
    {
        return view('universities.edit', compact('university'));
    }

    public function update(Request $request, University $university): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $university->update([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()
            ->route('admin.universities.index')
            ->with('status', __('تم تحديث الجامعة.'));
    }

    public function destroy(University $university): RedirectResponse
    {
        try {
            $university->delete();
        } catch (QueryException) {
            return redirect()
                ->route('admin.universities.index')
                ->withErrors(['delete' => __('لا يمكن حذف الجامعة لوجود طلبات مرتبطة بها.')]);
        }

        return redirect()
            ->route('admin.universities.index')
            ->with('status', __('تم حذف الجامعة.'));
    }
}
