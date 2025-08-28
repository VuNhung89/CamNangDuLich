<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:200',
            'dob' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = '/storage/' . $path;
        }

        $user->update($validated);

        return back()->with('success', 'Cập nhật hồ sơ thành công');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(Request $request)
    {
        $user = $request->user();

        $posts_count = $user->posts()->count();
        $visited_locations_count = $user->posts()->distinct('location_id')->count('location_id');

        $badges = [];
        if ($posts_count < 5) {
            $badges[] = ['name' => 'Tân Binh Du Lịch', 'icon' => 'star'];
        } elseif ($posts_count <= 10) {
            $badges[] = ['name' => 'Nhà Thám Hiểm', 'icon' => 'compass'];
        } else {
            $badges[] = ['name' => 'Huyền Thoại Du Lịch', 'icon' => 'trophy'];
        }

        // Nếu bạn cần trả bài viết (chỉ khi cần)
        $userPosts = $user->posts()->with('location')->get();

        return Inertia::render('Auth/Profile', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'bio' => $user->bio,
                'dob' => optional($user->dob)->format('Y-m-d'),
                'created_at' => $user->created_at,
                'posts_count' => $posts_count,
                'badges' => $badges,
                'visited_locations_count' => $visited_locations_count,
            ],
            'posts' => $userPosts,
        ]);
    }


    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->update(['password' => bcrypt($validated['new_password'])]);

        return back()->with(['status' => 'Đổi mật khẩu thành công']);
    }
}
