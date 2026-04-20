<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::query()
            ->withCount('specializations')
            ->orderBy('name')
            ->paginate(15);

        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Department::query()->create([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('status', __('تمت إضافة القسم.'));
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $department->update([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.departments.index')
            ->with('status', __('تم تحديث القسم.'));
    }

    public function destroy(Department $department): RedirectResponse
    {
        try {
            $department->delete();
        } catch (QueryException) {
            return redirect()
                ->route('admin.departments.index')
                ->withErrors(['delete' => __('لا يمكن حذف القسم لوجود طلبات أو ملفات مرتبطة به أو تخصصات.')]);
        }

        return redirect()
            ->route('admin.departments.index')
            ->with('status', __('تم حذف القسم.'));
    }
}
