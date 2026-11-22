<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendapatanController extends Controller
{

    public function index()
    {
        $store = Auth::user()->store;

        $saldoAktif = (float) $store->orders()
            ->where('status', Order::STATUS_SELESAI)
            ->sum('total_harga');

        $danaBelumDilepas = (float) $store->orders()
            ->whereIn('status', [
                Order::STATUS_MENUNGGU_KONFIRMASI,
                Order::STATUS_DIPROSES,
                Order::STATUS_DIKIRIM,
            ])
            ->sum('total_harga');

        $pesananSelesai = $store->orders()
            ->with('user')
            ->where('status', Order::STATUS_SELESAI)
            ->latest('updated_at')
            ->paginate(10);

        return view('penjual.pendapatan.index', compact(
            'store',
            'saldoAktif',
            'danaBelumDilepas',
            'pesananSelesai'
        ));
    }

    public function tarikDana(Request $request)
    {
        $store = Auth::user()->store;

        $saldoAktif = (float) $store->orders()
            ->where('status', Order::STATUS_SELESAI)
            ->sum('total_harga');

        if ($saldoAktif <= 0) {
            return back()->with('error', 'Saldo aktif Anda masih Rp 0.');
        }

        return redirect()->route('penjual.pendapatan.index')
            ->with('success', 'Permintaan penarikan dana sebesar Rp ' . number_format($saldoAktif, 0, ',', '.') . ' berhasil diajukan!');
    }
}
