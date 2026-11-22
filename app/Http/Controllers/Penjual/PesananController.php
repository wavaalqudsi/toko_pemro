<?php

namespace App\Http\Controllers\Penjual;

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
        $store = Auth::user()->store;

        $orders = $store->orders()
            ->with(['user', 'items.product'])
            ->status($request->input('status'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tabStatus = [
            'perlu_dikirim' => 'Perlu Dikirim',
            'dikirim'       => 'Dikirim',
            'selesai'       => 'Selesai',
            'dibatalkan'    => 'Dibatalkan',
        ];

        $jumlahPerTab = [
            'perlu_dikirim' => $store->orders()->whereIn('status', [Order::STATUS_DIPROSES, Order::STATUS_MENUNGGU_KONFIRMASI])->count(),
            'dikirim'       => $store->orders()->where('status', Order::STATUS_DIKIRIM)->count(),
            'selesai'       => $store->orders()->where('status', Order::STATUS_SELESAI)->count(),
            'dibatalkan'    => $store->orders()->where('status', Order::STATUS_DIBATALKAN)->count(),
        ];

        $daftarStatus = Order::STATUS_LABELS_PENJUAL;

        return view('penjual.pesanan.index', compact('orders', 'daftarStatus', 'tabStatus', 'jumlahPerTab'));
    }

    public function show(Order $order)
    {
        $this->pastikanMilikTokoSendiri($order);

        $order->load(['user', 'items.product']);

        return view('penjual.pesanan.show', compact('order'));
    }

    public function konfirmasiBayar(Order $order)
    {
        $this->pastikanMilikTokoSendiri($order);

        if (! $order->bisaDikonfirmasi()) {
            return back()->with('error', 'Pesanan ini tidak sedang menunggu konfirmasi pembayaran.');
        }

        $order->update(['status' => Order::STATUS_DIPROSES]);

        return redirect()->route('penjual.pesanan.show', $order)
            ->with('success', 'Pembayaran dikonfirmasi! Silakan atur pengiriman pesanan.');
    }

    public function tolakBayar(Order $order)
    {
        $this->pastikanMilikTokoSendiri($order);

        if (! $order->bisaDikonfirmasi()) {
            return back()->with('error', 'Pesanan ini tidak sedang menunggu konfirmasi pembayaran.');
        }

        if ($order->bukti_bayar && Storage::disk('public')->exists($order->bukti_bayar)) {
            Storage::disk('public')->delete($order->bukti_bayar);
        }

        $order->update([
            'bukti_bayar' => null,
            'status' => Order::STATUS_MENUNGGU_PEMBAYARAN,
        ]);

        return redirect()->route('penjual.pesanan.show', $order)
            ->with('success', 'Bukti pembayaran ditolak. Status dikembalikan ke Belum Dibayar.');
    }

    public function kirim(Request $request, Order $order)
    {
        $this->pastikanMilikTokoSendiri($order);

        if (! $order->bisaDikirim()) {
            return back()->with('error', 'Pesanan belum berada pada status Perlu Dikirim.');
        }

        $data = $request->validate([
            'no_resi' => 'nullable|string|max:50',
        ]);

        $noResi = ! empty($data['no_resi'])
            ? $data['no_resi']
            : ('JNT' . now()->format('Ymd') . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT));

        $order->update([
            'no_resi' => $noResi,
            'status' => Order::STATUS_DIKIRIM,
        ]);

        return redirect()->route('penjual.pesanan.show', $order)
            ->with('success', "Pengiriman berhasil diatur! Nomor Resi: {$noResi}");
    }

    public function batal(Order $order)
    {
        $this->pastikanMilikTokoSendiri($order);

        if (! $order->bisaDibatalkanPenjual()) {
            return back()->with('error', 'Pesanan yang sudah dikirim tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items()->get() as $item) {
                Product::where('id', $item->product_id)->increment('stok', $item->jumlah);
            }

            $order->update(['status' => Order::STATUS_DIBATALKAN]);
        });

        return redirect()->route('penjual.pesanan.show', $order)
            ->with('success', 'Pesanan dibatalkan dan stok produk telah dikembalikan.');
    }

    private function pastikanMilikTokoSendiri(Order $order): void
    {
        abort_if($order->store_id !== Auth::user()->store->id, 403, 'Unauthorized access.');
    }
}
