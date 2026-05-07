<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
    public function update(Request $request)
{
    $user = $request->user();

    $request->validate([
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->has('name')) $user->name = $request->name;
    if ($request->has('email')) $user->email = $request->email;
    if ($request->filled('password')) $user->password = bcrypt($request->password);

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        $filename = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('avatars'), $filename); // <- uploads to public/avatars
        $user->avatar = 'avatars/' . $filename; // save relative path
    }
    

    $user->save();

    return response()->json([
        'message' => 'Profile updated successfully!',
        'user' => $user
    ]);
}

}
