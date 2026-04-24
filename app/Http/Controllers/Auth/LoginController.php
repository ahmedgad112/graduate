<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('بيانات الدخول غير صحيحة.'),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if (! $user instanceof User) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => __('تعذر تسجيل الدخول.'),
            ])->onlyInput('email');
        }

        if ($user->canAccessAdminPanel()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isStudent()) {
            if (! $user->profile) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => __('لا يوجد ملف خريج مرتبط بهذا الحساب. تواصل مع الإدارة.'),
                ])->onlyInput('email');
            }

            return redirect()->intended(route('profile.edit'));
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => __('لا يمكنك الدخول بهذا الحساب.'),
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
