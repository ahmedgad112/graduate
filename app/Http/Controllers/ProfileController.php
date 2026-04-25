<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User && $user->isStudent(), 403);
        $profile = $user->profile;
        abort_unless($profile instanceof Profile, 404);

        $profile->load(['department', 'specialization', 'graduationYear']);

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User && $user->isStudent(), 403);
        $profile = $user->profile;
        abort_unless($profile instanceof Profile, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:32', Rule::unique('users', 'phone')->ignore($user->id)],
            'governorate' => ['required', 'string', Rule::in(array_keys(Application::GOVERNORATES))],
            'residence_region' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'skills' => ['nullable', 'string', 'max:5000'],
            'certificates_text' => ['nullable', 'string', 'max:5000'],
            'employment_status' => ['required', Rule::in(Application::employmentStatuses())],
            'exempt_from_military' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'cert' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
            ]);
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];
        if ($request->filled('password')) {
            $userData['password'] = $validated['password'];
        }
        $user->update($userData);

        $profileData = [
            'governorate' => $validated['governorate'],
            'residence_region' => $validated['residence_region'],
            'address' => $validated['address'],
            'skills' => $validated['skills'] ?? null,
            'certificates_text' => $validated['certificates_text'] ?? null,
            'employment_status' => $validated['employment_status'],
            'exempt_from_military' => $request->boolean('exempt_from_military'),
        ];

        if ($request->hasFile('cv')) {
            if ($profile->cv_path) {
                Storage::disk('public')->delete($profile->cv_path);
            }
            $profileData['cv_path'] = $request->file('cv')->store('profiles/cv', 'public');
        }

        if ($request->hasFile('cert')) {
            if ($profile->cert_path) {
                Storage::disk('public')->delete($profile->cert_path);
            }
            $profileData['cert_path'] = $request->file('cert')->store('profiles/certificates', 'public');
        }

        if ($request->hasFile('photo')) {
            if ($profile->photo_path) {
                Storage::disk('public')->delete($profile->photo_path);
            }
            $profileData['photo_path'] = $request->file('photo')->store('profiles/photos', 'public');
        }

        $profile->update($profileData);

        return redirect()
            ->route('profile.edit')
            ->with('status', __('تم حفظ التعديلات.'));
    }
}
