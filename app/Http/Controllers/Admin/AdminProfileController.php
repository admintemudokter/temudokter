<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = auth('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
                Storage::disk('public')->delete($admin->photo);
            }

            $path = $request->file('photo')->store('admins', 'public');
            $admin->photo = $path;
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
