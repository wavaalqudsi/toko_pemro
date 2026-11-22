<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $store = Auth::user()->store;

        $ringkasan = [
            'total_produk' => $store->products()->count(),
            'stok_habis' => $store->products()->where('stok', '<=', 0)->count(),
            'total_pesanan' => $store->orders()->count(),
            'pesanan_baru' => $store->orders()->whereIn('status', [
                Order::STATUS_MENUNGGU_KONFIRMASI,
                Order::STATUS_DIPROSES,
            ])->count(),
            'belum_dibayar' => $store->orders()->where('status', Order::STATUS_MENUNGGU_PEMBAYARAN)->count(),
            'perlu_dikirim' => $store->orders()->whereIn('status', [
                Order::STATUS_MENUNGGU_KONFIRMASI,
                Order::STATUS_DIPROSES,
            ])->count(),
            'perlu_diproses' => $store->orders()->whereIn('status', [
                Order::STATUS_MENUNGGU_KONFIRMASI,
                Order::STATUS_DIPROSES,
            ])->count(),
            'pengajuan_retur' => 0,
            'pendapatan_hari_ini' => (float) $store->orders()
                ->where('status', Order::STATUS_SELESAI)
                ->whereDate('updated_at', today())
                ->sum('total_harga'),
            'total_pendapatan' => (float) $store->orders()
                ->where('status', Order::STATUS_SELESAI)
                ->sum('total_harga'),
            'total_kunjungan' => 342,
        ];

        $pesananTerbaru = $store->orders()
            ->with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('penjual.dashboard', compact('store', 'ringkasan', 'pesananTerbaru'));
    }
}
