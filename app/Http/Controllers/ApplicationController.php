<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Department;
use App\Models\GraduationYear;
use App\Models\Profile;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(): View
    {
        $universities = University::query()->active()->orderBy('name')->get();

        $departmentsJson = Department::query()
            ->active()
            ->with(['specializations' => fn ($q) => $q->active()->orderBy('name')])
            ->orderBy('name')
            ->get()
            ->map(fn (Department $d) => [
                'id' => $d->id,
                'name' => $d->name,
                'specializations' => $d->specializations->map(fn ($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                ])->values(),
            ])
            ->values();

        $graduationYears = GraduationYear::query()->active()->orderByDesc('year')->get();

        return view('applications.create', compact('universities', 'departmentsJson', 'graduationYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('applications', 'email'), Rule::unique('users', 'email')],
            'phone' => ['required', 'string', 'max:32', Rule::unique('applications', 'phone'), Rule::unique('users', 'phone')],
            'national_id' => ['required', 'string', 'max:32', Rule::unique('applications', 'national_id'), Rule::unique('profiles', 'national_id')],
            'address' => ['required', 'string', 'max:1000'],
            'university_id' => ['required', 'exists:universities,id'],
            'department_id' => ['required', 'integer', Rule::exists('departments', 'id')->where(fn ($q) => $q->where('is_active', true))],
            'specialization_id' => ['required', 'integer', Rule::exists('specializations', 'id')->where(function ($q) use ($request) {
                $q->where('department_id', $request->input('department_id'))->where('is_active', true);
            })],
            'graduation_year_id' => ['required', 'integer', Rule::exists('graduation_years', 'id')->where(fn ($q) => $q->where('is_active', true))],
            'grade' => ['required', Rule::in(Application::GRADES)],
            'gpa' => ['nullable', 'numeric', 'between:0,5'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'cert' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'photo' => ['required', 'image', 'max:5120'],
            'skills' => ['nullable', 'string', 'max:5000'],
            'certificates_text' => ['nullable', 'string', 'max:5000'],
            'employment_status' => ['required', Rule::in(Application::employmentStatuses())],
            'exempt_from_military' => ['required', 'boolean'],
        ]);

        $university = University::query()->findOrFail($validated['university_id']);

        if (! $university->is_active) {
            return back()->withErrors(['university_id' => __('الجامعة المحددة غير متاحة.')])->withInput();
        }

        $cvPath = $request->file('cv')->store('applications/cv', 'public');
        $certPath = $request->file('cert')->store('applications/certificates', 'public');
        $photoPath = $request->file('photo')->store('applications/photos', 'public');

        Application::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'national_id' => $validated['national_id'],
            'address' => $validated['address'],
            'university_id' => $validated['university_id'],
            'department_id' => $validated['department_id'],
            'specialization_id' => $validated['specialization_id'],
            'graduation_year_id' => $validated['graduation_year_id'],
            'grade' => $validated['grade'],
            'gpa' => $validated['gpa'] ?? null,
            'cv_path' => $cvPath,
            'cert_path' => $certPath,
            'photo_path' => $photoPath,
            'skills' => $validated['skills'] ?? null,
            'certificates_text' => $validated['certificates_text'] ?? null,
            'employment_status' => $validated['employment_status'],
            'exempt_from_military' => $request->boolean('exempt_from_military'),
            'status' => Application::STATUS_PENDING,
        ]);

        return redirect()
            ->route('applications.create')
            ->with('status', __('تم استلام طلبك بنجاح. سيتم مراجعته من قبل الإدارة.'));
    }

    public function index(): View
    {
        $applications = Application::query()
            ->with(['university', 'department', 'specialization', 'graduationYear'])
            ->where('status', Application::STATUS_PENDING)
            ->latest()
            ->paginate(15);

        return view('applications.index', compact('applications'));
    }

    public function show(Application $application): View
    {
        $application->load([
            'university',
            'department',
            'specialization',
            'graduationYear',
        ]);

        return view('applications.show', compact('application'));
    }

    public function approve(Application $application): RedirectResponse
    {
        if (! $application->isPending()) {
            return back()->withErrors(['application' => __('لا يمكن معالجة هذا الطلب.')]);
        }

        if (User::query()->where('email', $application->email)->orWhere('phone', $application->phone)->exists()) {
            return back()->withErrors(['application' => __('يوجد مستخدم مسجل بنفس البريد أو الهاتف.')]);
        }

        if (Profile::query()->where('national_id', $application->national_id)->exists()) {
            return back()->withErrors(['application' => __('رقم الهوية مسجل مسبقاً في ملف خريج.')]);
        }

        $application->load('university');

        $password = $application->national_id;

        DB::transaction(function () use ($application, $password): void {
            $user = User::query()->create([
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
                'password' => $password,
                'role' => User::ROLE_STUDENT,
            ]);

            Profile::query()->create([
                'user_id' => $user->id,
                'national_id' => $application->national_id,
                'university_name' => $application->university->name,
                'department_id' => $application->department_id,
                'specialization_id' => $application->specialization_id,
                'graduation_year_id' => $application->graduation_year_id,
                'grade' => $application->grade,
                'gpa' => $application->gpa,
                'cv_path' => $application->cv_path,
                'cert_path' => $application->cert_path,
                'photo_path' => $application->photo_path,
                'skills' => $application->skills,
                'certificates_text' => $application->certificates_text,
                'employment_status' => $application->employment_status,
                'exempt_from_military' => $application->exempt_from_military,
            ]);

            $application->update(['status' => Application::STATUS_APPROVED]);
        });

        $application->refresh();

        return redirect()
            ->route('admin.applications.show', $application)
            ->with('status', __('تمت الموافقة وإنشاء حساب الخريج. كلمة مرور الدخول هي الرقم القومي المسجّل في الطلب.'));
    }

    public function reject(Application $application): RedirectResponse
    {
        if (! $application->isPending()) {
            return back()->withErrors(['application' => __('لا يمكن رفض هذا الطلب.')]);
        }

        $application->update(['status' => Application::STATUS_REJECTED]);
        $application->refresh();

        return redirect()
            ->route('admin.applications.show', $application)
            ->with('status', __('تم رفض الطلب.'));
    }
}
