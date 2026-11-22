<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with(['store', 'items.product'])
            ->status($request->input('status'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tabStatus = [
            'belum_bayar' => 'Belum Bayar',
            'dikemas'     => 'Dikemas',
            'dikirim'     => 'Dikirim',
            'selesai'     => 'Selesai',
            'dibatalkan'  => 'Dibatalkan',
        ];

        $jumlahPerTab = [
            'belum_bayar' => $user->orders()->whereIn('status', [Order::STATUS_MENUNGGU_PEMBAYARAN, Order::STATUS_MENUNGGU_KONFIRMASI])->count(),
            'dikemas'     => $user->orders()->where('status', Order::STATUS_DIPROSES)->count(),
            'dikirim'     => $user->orders()->where('status', Order::STATUS_DIKIRIM)->count(),
            'selesai'     => $user->orders()->where('status', Order::STATUS_SELESAI)->count(),
            'dibatalkan'  => $user->orders()->where('status', Order::STATUS_DIBATALKAN)->count(),
        ];

        $daftarStatus = Order::STATUS_LABELS_PEMBELI;

        return view('pembeli.pesanan.index', compact('orders', 'daftarStatus', 'tabStatus', 'jumlahPerTab'));
    }

    public function show(Order $order)
    {
        $this->pastikanMilikSendiri($order);

        $order->load(['store', 'items.product']);

        return view('pembeli.pesanan.show', compact('order'));
    }

    public function uploadBukti(Request $request, Order $order)
    {
        $this->pastikanMilikSendiri($order);

        if (! $order->bisaUploadBukti()) {
            return back()->with('error', 'Bukti pembayaran tidak dapat diupload untuk pesanan ini.');
        }

        $request->validate([
            'bukti_bayar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($order->bukti_bayar && Storage::disk('public')->exists($order->bukti_bayar)) {
            Storage::disk('public')->delete($order->bukti_bayar);
        }

        $order->update([
            'bukti_bayar' => $request->file('bukti_bayar')->store('uploads/bukti_bayar', 'public'),
            'status' => Order::STATUS_MENUNGGU_KONFIRMASI,
        ]);

        return redirect()->route('pesanan.show', $order)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi penjual.');
    }

    public function batal(Order $order)
    {
        $this->pastikanMilikSendiri($order);

        if (! $order->bisaDibatalkanPembeli()) {
            return back()->with('error', 'Pesanan yang sudah dikirim tidak dapat dibatalkan.');
        }

        $this->batalkanDanKembalikanStok($order);

        return redirect()->route('pesanan.show', $order)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function selesai(Order $order)
    {
        $this->pastikanMilikSendiri($order);

        if (! $order->bisaDiselesaikan()) {
            return back()->with('error', 'Pesanan belum dikirim sehingga belum dapat diselesaikan.');
        }

        $order->update(['status' => Order::STATUS_SELESAI]);

        return redirect()->route('pesanan.show', $order)
            ->with('success', 'Terima kasih! Pesanan telah selesai.');
    }

    private function pastikanMilikSendiri(Order $order): void
    {
        abort_if($order->user_id !== Auth::id(), 403, 'Unauthorized access.');
    }

    private function batalkanDanKembalikanStok(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items()->get() as $item) {
                Product::where('id', $item->product_id)->increment('stok', $item->jumlah);
            }

            $order->update(['status' => Order::STATUS_DIBATALKAN]);
        });
    }
}
