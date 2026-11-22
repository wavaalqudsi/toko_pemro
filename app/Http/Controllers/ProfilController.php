<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{

    public function edit()
    {
        $user = Auth::user();
        $jumlahNotifikasi = $user->orders()->count();

        return view('profil.edit', compact('user', 'jumlahNotifikasi'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('profil.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function notifikasi()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with(['store', 'items.product'])
            ->latest('updated_at')
            ->paginate(10);

        return view('profil.notifikasi', compact('user', 'orders'));
    }
}
