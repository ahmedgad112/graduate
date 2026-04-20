<?php

namespace App\Http\Controllers;

use App\Exports\GraduatesExport;
use App\Models\Application;
use App\Models\Department;
use App\Models\GraduationYear;
use App\Models\Profile;
use App\Models\University;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $totalGraduates = Profile::query()->count();
        $pendingApps = Application::query()->where('status', Application::STATUS_PENDING)->count();

        $graduatesQuery = Profile::query()->with(['user', 'department', 'specialization', 'graduationYear']);
        $this->applyGraduateFilters($graduatesQuery, $request);

        $graduates = $graduatesQuery->latest('profiles.id')->paginate(15)->withQueryString();

        $universities = University::query()->where('is_active', true)->orderBy('name')->get();
        $departments = Department::query()->where('is_active', true)->orderBy('name')->get();
        $grades = Application::GRADES;
        $graduationYears = GraduationYear::query()->active()->orderByDesc('year')->get();

        return view('admin.dashboard', compact(
            'totalGraduates',
            'pendingApps',
            'graduates',
            'universities',
            'departments',
            'grades',
            'graduationYears'
        ));
    }

    public function studentsByYear(Request $request): View
    {
        $graduationYears = GraduationYear::query()->active()->orderByDesc('year')->get();

        $selectedGraduationYearId = $request->query('graduation_year_id');
        $students = null;
        $selectedGraduationYear = null;

        if ($selectedGraduationYearId !== null && $selectedGraduationYearId !== '') {
            $selectedGraduationYear = GraduationYear::query()->find((int) $selectedGraduationYearId);
            if ($selectedGraduationYear) {
                $students = Profile::query()
                    ->with(['user', 'department', 'specialization', 'graduationYear'])
                    ->where('graduation_year_id', $selectedGraduationYear->id)
                    ->orderBy('id')
                    ->paginate(40)
                    ->withQueryString();
            }
        }

        return view('admin.students-by-year', compact('graduationYears', 'selectedGraduationYearId', 'selectedGraduationYear', 'students'));
    }

    public function exportGraduates(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['graduation_year_id', 'grade', 'university_id', 'department_id']);

        return Excel::download(
            new GraduatesExport($filters),
            'graduates_'.now()->format('Y-m-d_His').'.xlsx'
        );
    }

    protected function applyGraduateFilters(Builder $query, Request $request): void
    {
        if ($request->filled('graduation_year_id')) {
            $query->where('graduation_year_id', $request->input('graduation_year_id'));
        }

        if ($request->filled('grade')) {
            $query->where('grade', $request->input('grade'));
        }

        if ($request->filled('university_id')) {
            $university = University::query()->find($request->input('university_id'));
            if ($university) {
                $query->where('university_name', $university->name);
            }
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }
    }
}
