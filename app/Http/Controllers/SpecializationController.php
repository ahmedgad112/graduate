<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Specialization;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpecializationController extends Controller
{
    public function index(Department $department): View
    {
        $specializations = $department->specializations()
            ->orderBy('name')
            ->paginate(15);

        return view('specializations.index', compact('department', 'specializations'));
    }

    public function create(Department $department): View
    {
        return view('specializations.create', compact('department'));
    }

    public function store(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $department->specializations()->create([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.departments.specializations.index', $department)
            ->with('status', __('تمت إضافة التخصص.'));
    }

    public function edit(Department $department, Specialization $specialization): View
    {
        return view('specializations.edit', compact('department', 'specialization'));
    }

    public function update(Request $request, Department $department, Specialization $specialization): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $specialization->update([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.departments.specializations.index', $department)
            ->with('status', __('تم تحديث التخصص.'));
    }

    public function destroy(Department $department, Specialization $specialization): RedirectResponse
    {
        try {
            $specialization->delete();
        } catch (QueryException) {
            return redirect()
                ->route('admin.departments.specializations.index', $department)
                ->withErrors(['delete' => __('لا يمكن حذف التخصص لوجود طلبات أو ملفات مرتبطة به.')]);
        }

        return redirect()
            ->route('admin.departments.specializations.index', $department)
            ->with('status', __('تم حذف التخصص.'));
    }
}
