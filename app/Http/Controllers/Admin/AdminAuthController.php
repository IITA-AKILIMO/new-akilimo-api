<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class AdminAuthController extends Controller
{
    public function showLogin(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        $identifier = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if ($user === null || ! Hash::check($password, $user->password)) {
            return back()->withErrors([
                'username' => 'These credentials do not match our records.',
            ])->onlyInput('username');
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
