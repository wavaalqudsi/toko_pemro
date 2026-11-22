<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{

    public function index()
    {
        $cartItems = Auth::user()->cartItems()
            ->with('product.store')
            ->latest()
            ->get();

        $keranjangPerToko = $cartItems->groupBy(fn ($item) => $item->product->store_id);

        $totalHarga = $cartItems->sum(fn ($item) => $item->subtotal);
        $totalBarang = $cartItems->sum('jumlah');
        $totalDiskon = 0;
        $totalBayar = $totalHarga - $totalDiskon;

        return view('pembeli.keranjang.index', compact(
            'cartItems',
            'keranjangPerToko',
            'totalHarga',
            'totalBarang',
            'totalDiskon',
            'totalBayar'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $jumlahTambah = (int) ($data['jumlah'] ?? 1);
        $product = Product::findOrFail($data['product_id']);

        $cartItem = CartItem::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        $jumlahBaru = $request->boolean('beli_sekarang')
            ? $jumlahTambah
            : (($cartItem->jumlah ?? 0) + $jumlahTambah);

        if ($jumlahBaru > $product->stok) {
            return back()->withErrors([
                'jumlah' => "Stok tidak mencukupi. Stok tersedia: {$product->stok}"
                    . ($cartItem->exists ? ", sudah ada {$cartItem->jumlah} di keranjang." : '.'),
            ])->withInput();
        }

        $cartItem->jumlah = $jumlahBaru;
        $cartItem->save();

        if ($request->boolean('beli_sekarang')) {
            return redirect()->route('checkout.create', ['items' => [$cartItem->id]]);
        }

        return redirect()->route('keranjang.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->pastikanMilikSendiri($cartItem);

        $cartItem->load('product');

        if ($request->filled('aksi')) {
            $jumlahBaru = $request->input('aksi') === 'tambah'
                ? $cartItem->jumlah + 1
                : $cartItem->jumlah - 1;

            if ($jumlahBaru <= 0) {
                $cartItem->delete();

                return redirect()->route('keranjang.index')
                    ->with('success', 'Produk dihapus dari keranjang.');
            }

            if ($jumlahBaru > $cartItem->product->stok) {
                return back()->withErrors([
                    'jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $cartItem->product->stok . '.',
                ]);
            }

            $cartItem->update(['jumlah' => $jumlahBaru]);

            return redirect()->route('keranjang.index');
        }

        $data = $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $cartItem->product->stok,
        ], [
            'jumlah.max' => 'Stok tidak mencukupi. Stok tersedia: ' . $cartItem->product->stok . '.',
        ]);

        $cartItem->update($data);

        return redirect()->route('keranjang.index')
            ->with('success', 'Jumlah produk berhasil diperbarui!');
    }

    public function destroy(CartItem $cartItem)
    {
        $this->pastikanMilikSendiri($cartItem);

        $cartItem->delete();

        return redirect()->route('keranjang.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function destroySelected(Request $request)
    {
        $query = Auth::user()->cartItems();

        if ($request->filled('items') && is_array($request->input('items'))) {
            $query->whereIn('id', $request->input('items'));
        }

        $query->delete();

        return redirect()->route('keranjang.index')
            ->with('success', 'Produk terpilih berhasil dihapus dari keranjang!');
    }

    private function pastikanMilikSendiri(CartItem $cartItem): void
    {
        abort_if($cartItem->user_id !== Auth::id(), 403, 'Unauthorized access.');
    }
}
