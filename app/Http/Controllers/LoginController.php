<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // 顯示登入頁
    public function showLogin()
    {
        return view('auth.login');
    }

    // 顯示註冊頁
    public function showSignUp()
    {
        return view('auth.signup');
    }

    // 處理登入
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('role', 'user')->first();
        if (!$user) {
            return back()->withErrors([
                'login' => '帳號或密碼錯誤',
            ])->withInput();
        }
        

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login' => '帳號或密碼錯誤',
        ])->withInput();
    }

    // 處理註冊
    public function signUp(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        return redirect('/');
    }
}
