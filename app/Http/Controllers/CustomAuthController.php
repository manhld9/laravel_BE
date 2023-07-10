<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc,dn'],
            'password' => ['required', Password::min(8)->mixedCase()],
        ]);

        if ($validator->fails())
        {
            return redirect('login')->withErrors($validator)->withInput();
        }

        $credentials = $validator->validated();
        // $credentials = $validator->safe()->only(['email', 'password']);

        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            return redirect()->intended('dashboard')->withSuccess('Signed in!');
        }

        return redirect('login')->withSuccess('Login details are not valid!');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function customRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email:rfc,dn', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()],
        ]);

        if ($validator->fails())
        {
            return redirect('registration')->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('dashboard')->withSuccess('You have signed-in');
    }

    public function dashboard()
    {
        if (Auth::check())
        {
            return view('auth.dashboard')->with('user', Auth::user());
        }

        return redirect('login')->withSuccess('You are not allowed to access!');
    }

    public function signOut(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
