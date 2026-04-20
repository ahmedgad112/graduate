<?php

namespace App\Http\Controllers;

use App\Models\GraduationYear;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GraduationYearController extends Controller
{
    public function index(): View
    {
        $graduationYears = GraduationYear::query()->orderByDesc('year')->paginate(20);

        return view('graduation-years.index', compact('graduationYears'));
    }

    public function create(): View
    {
        return view('graduation-years.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:1970', 'max:2100', 'unique:graduation_years,year'],
        ]);

        GraduationYear::query()->create([
            'year' => $validated['year'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.graduation-years.index')
            ->with('status', __('تمت إضافة سنة التخرج.'));
    }

    public function edit(GraduationYear $graduation_year): View
    {
        return view('graduation-years.edit', ['graduationYear' => $graduation_year]);
    }

    public function update(Request $request, GraduationYear $graduation_year): RedirectResponse
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:1970', 'max:2100', Rule::unique('graduation_years', 'year')->ignore($graduation_year->id)],
        ]);

        $graduation_year->update([
            'year' => $validated['year'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.graduation-years.index')
            ->with('status', __('تم تحديث سنة التخرج.'));
    }

    public function destroy(GraduationYear $graduation_year): RedirectResponse
    {
        try {
            $graduation_year->delete();
        } catch (QueryException) {
            return redirect()
                ->route('admin.graduation-years.index')
                ->withErrors(['delete' => __('لا يمكن حذف هذه السنة لوجود طلبات أو ملفات خريجين مرتبطة بها.')]);
        }

        return redirect()
            ->route('admin.graduation-years.index')
            ->with('status', __('تم حذف سنة التخرج.'));
    }
}
