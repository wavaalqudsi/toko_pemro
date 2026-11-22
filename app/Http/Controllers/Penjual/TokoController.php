<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{

    public function create()
    {
        if (Auth::user()->store) {
            return redirect()->route('penjual.dashboard');
        }

        $daftarKota = [
            'Jakarta Selatan',
            'Jakarta Pusat',
            'Jakarta Barat',
            'Jakarta Timur',
            'Jakarta Utara',
            'Bandung',
            'Surabaya',
            'Surakarta (Solo)',
            'Yogyakarta',
            'Semarang',
        ];

        return view('penjual.toko.create', compact('daftarKota'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->store) {
            return redirect()->route('penjual.dashboard');
        }

        if (! $request->filled('alamat_toko') && $request->filled('kota_asal')) {
            $request->merge(['alamat_toko' => $request->input('kota_asal')]);
        }

        $data = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'alamat_toko' => 'nullable|string',
        ]);

        if (! $user->isPenjual()) {
            $user->update(['role' => User::ROLE_PENJUAL]);
        }

        $user->store()->create($data);

        return redirect()->route('penjual.dashboard')
            ->with('success', 'Toko berhasil dibuat! Selamat datang di Seller Centre.');
    }

    public function edit()
    {
        $store = Auth::user()->store;

        $daftarKota = [
            'Jakarta Selatan',
            'Jakarta Pusat',
            'Jakarta Barat',
            'Jakarta Timur',
            'Jakarta Utara',
            'Bandung',
            'Surabaya',
            'Surakarta (Solo)',
            'Yogyakarta',
            'Semarang',
        ];

        return view('penjual.toko.edit', compact('store', 'daftarKota'));
    }

    public function update(Request $request)
    {
        if (! $request->filled('alamat_toko') && $request->filled('kota_asal')) {
            $request->merge(['alamat_toko' => $request->input('kota_asal')]);
        }

        $data = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'alamat_toko' => 'nullable|string',
        ]);

        Auth::user()->store->update($data);

        return redirect()->route('penjual.toko.edit')
            ->with('success', 'Profil toko berhasil diperbarui!');
    }
}
