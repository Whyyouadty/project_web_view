<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index ()
    {
        if (Auth::check()) {
            return redirect(route('pages.dashboard'));
        } else {
            return view('auth.Login');
        }
    }

    public function login (Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'min:4'],
            'password' => ['required', 'min:5'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('pages.dashboard'));
        }
        return back()->with('statusErr', 'Username atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'))->with('status', 'Berhasil Logout');
    }
}
