<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth');
    }

    public function loginProses(Request $request)
    {
        if (Auth::attempt($request->only('username', 'password'))) {
            return redirect('/');
        }

        return back()->with('error', 'Login gagal');
    }

    public function registerProses(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:5'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // default
        ]);

        Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ]);

        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function forgotPassword()
    {
        return view('forgot-password');
    }

    public function resetPasswordProses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed'
        ]);

        $user = User::where('username', $request->username)->where('email', $request->email)->first();

        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            return redirect('/login')->with('success', 'Kata sandi berhasil direset! Silakan masuk.');
        }

        return back()->with('error', 'Username atau Email tidak ditemukan.')->withInput();
    }

    // 🔥 UPDATE PROFIL + FOTO CROP
    public function updateProfil(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . auth()->id(),
            'email' => 'required|email',
            'photo' => 'nullable'
        ]);

        $user = auth()->user();

        // ✅ HANDLE BASE64 (CROPPER)
if ($request->photo) {

    $image_parts = explode(";base64,", $request->photo);

    if (count($image_parts) !== 2) {
        dd('format base64 salah');
    }

    $image_base64 = base64_decode($image_parts[1]);

    if (!$image_base64) {
        dd('decode gagal');
    }

    // nama file
    $fileName = 'profile_' . time() . '.png';

    // path FULL (bukan Storage dulu)
    $path = storage_path('app/public/profile/' . $fileName);

    // SIMPAN LANGSUNG (lebih pasti)
    file_put_contents($path, $image_base64);

    // simpan ke database
    $user->photo = 'profile/' . $fileName;
}

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'photo' => $user->photo
        ]);

        return back()->with('success', 'Profil berhasil diupdate');
    }

    // 🔒 UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->with('error', 'Password saat ini salah!');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}