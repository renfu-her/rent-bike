<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * 顯示會員個人資料頁面
     */
    public function show()
    {
        // 檢查會員是否已登入
        if (!Auth::guard('member')->check()) {
            return redirect()->route('login', ['return' => request()->fullUrl()]);
        }

        $member = Auth::guard('member')->user();
        return view('profile.show', compact('member'));
    }

    /**
     * 更新會員個人資料
     */
    public function update(Request $request)
    {
        // 檢查會員是否已登入
        if (!Auth::guard('member')->check()) {
            return redirect()->route('login', ['return' => request()->fullUrl()]);
        }

        $member = Auth::guard('member')->user();

        // 驗證表單資料
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('members')->ignore($member->id),
            ],
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'id_number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('members')->ignore($member->id),
            ],
            'gender' => 'required|in:male,female,other',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        // 更新會員資料
        $member->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'id_number' => strtoupper($validated['id_number']), // 轉為大寫
            'gender' => $validated['gender'],
        ]);

        // 如果有新密碼，則更新密碼
        if (!empty($validated['new_password'])) {
            $member->update([
                'password' => Hash::make($validated['new_password'])
            ]);
        }

        return back()->with('success', '個人資料更新成功！');
    }
}
