<?php

namespace App\Http\Controllers;

use App\Models\Member;
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

        $member = Member::where('email', $credentials['email'])->first();
        if (!$member) {
            return back()->withErrors([
                'login' => '帳號或密碼錯誤',
            ])->withInput();
        }

        if (Auth::guard('member')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect($request->input('return') ?: '/');
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
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'password' => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'email.unique' => '此 Email 已經存在，請重新輸入',
            'password.min' => '密碼至少需要 8 個字元',
            'password.regex' => '密碼必須包含至少一個小寫字母、一個大寫字母和一個數字',
        ]);

        $member = Member::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('member')->login($member);
        return redirect('/');
    }

    // 登出
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // 檢查 Email 是否存在
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = Member::where('email', $request->email)->exists();
        
        return response()->json([
            'exists' => $exists
        ]);
    }
}
