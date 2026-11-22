<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{

    public function create(Request $request)
    {
        $user = Auth::user();

        $query = $user->cartItems()->with('product.store');

        if ($request->filled('items') && is_array($request->input('items'))) {
            $query->whereIn('id', $request->input('items'));
        }

        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang masih kosong atau belum ada produk yang dipilih.');
        }

        $keranjangPerToko = $cartItems->groupBy(fn ($item) => $item->product->store_id);
        $subtotalProduk = $cartItems->sum(fn ($item) => $item->subtotal);
        $opsiPengiriman = Order::OPSI_PENGIRIMAN;
        $ongkirDefault = Order::OPSI_PENGIRIMAN['reguler']['ongkir'] * $keranjangPerToko->count();
        $diskonVoucher = 0;
        $totalHarga = $subtotalProduk + $ongkirDefault - $diskonVoucher;
        $metodeBayar = Order::METODE_BAYAR;
        $selectedItems = $cartItems->pluck('id')->all();

        return view('pembeli.checkout.create', compact(
            'user',
            'cartItems',
            'keranjangPerToko',
            'subtotalProduk',
            'opsiPengiriman',
            'ongkirDefault',
            'diskonVoucher',
            'totalHarga',
            'metodeBayar',
            'selectedItems'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_penerima' => 'nullable|string|max:100',
            'alamat_kirim' => 'required|string|max:500',
            'opsi_pengiriman' => 'nullable|in:reguler,ekspres,instant',
            'metode_bayar' => 'required|in:transfer,bca_va,gopay,cod',
            'items' => 'nullable|array',
            'items.*' => 'integer',
        ]);

        $user = Auth::user();
        $query = $user->cartItems()->with('product');

        if (! empty($data['items'])) {
            $query->whereIn('id', $data['items']);
        }

        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        foreach ($cartItems as $item) {
            if ($item->jumlah > $item->product->stok) {
                return redirect()->route('keranjang.index')->with(
                    'error',
                    "Stok {$item->product->nama_produk} tidak mencukupi (tersisa {$item->product->stok})."
                );
            }
        }

        $ongkirPerToko = isset($data['opsi_pengiriman'])
            ? (Order::OPSI_PENGIRIMAN[$data['opsi_pengiriman']]['ongkir'] ?? 0)
            : 0;

        $alamatLengkap = ! empty($data['nama_penerima'])
            ? "{$data['nama_penerima']} | {$data['alamat_kirim']}"
            : $data['alamat_kirim'];

        $metodeDb = $data['metode_bayar'] === 'cod' ? 'cod' : 'transfer';

        $orders = DB::transaction(function () use ($cartItems, $alamatLengkap, $metodeDb, $ongkirPerToko, $user) {
            $orders = collect();

            foreach ($cartItems->groupBy(fn ($item) => $item->product->store_id) as $storeId => $items) {
                $subtotalToko = $items->sum(fn ($item) => $item->subtotal);

                $order = Order::create([
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'total_harga' => $subtotalToko + $ongkirPerToko,
                    'alamat_kirim' => $alamatLengkap,
                    'metode_bayar' => $metodeDb,
                    'status' => $metodeDb === 'cod'
                        ? Order::STATUS_DIPROSES
                        : Order::STATUS_MENUNGGU_PEMBAYARAN,
                ]);

                foreach ($items as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'jumlah' => $item->jumlah,
                        'harga_satuan' => $item->product->harga,
                    ]);

                    Product::where('id', $item->product_id)->decrement('stok', $item->jumlah);
                }

                $orders->push($order);
            }

            $user->cartItems()->whereIn('id', $cartItems->pluck('id'))->delete();

            return $orders;
        });

        $pesan = $metodeDb === 'transfer'
            ? 'Pesanan berhasil dibuat! Silakan lakukan pembayaran dan upload bukti pembayaran.'
            : 'Pesanan berhasil dibuat! Pesanan sedang dikemas oleh penjual.';

        if ($orders->count() === 1) {
            return redirect()->route('pesanan.show', $orders->first())->with('success', $pesan);
        }

        return redirect()->route('pesanan.index')
            ->with('success', $pesan . " ({$orders->count()} pesanan dari toko berbeda)");
    }
}
