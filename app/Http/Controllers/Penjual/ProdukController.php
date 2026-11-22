<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{

    public function index(Request $request)
    {
        $store = Auth::user()->store;

        $products = $store->products()
            ->with('category')
            ->cari($request->input('cari'))
            ->filter($request->only(['category_id']))
            ->urutkan($request->input('urut'))
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('nama_kategori')->get();

        return view('penjual.produk.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('nama_kategori')->get();

        return view('penjual.produk.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:100',
            'stok' => 'required|integer|min:0',
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('uploads/produk', 'public');
        }

        Auth::user()->store->products()->create($data);

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $produk)
    {
        $this->pastikanMilikTokoSendiri($produk);
        $produk->load('category');

        return view('penjual.produk.show', compact('produk'));
    }

    public function edit(Product $produk)
    {
        $this->pastikanMilikTokoSendiri($produk);
        $categories = Category::orderBy('nama_kategori')->get();

        return view('penjual.produk.edit', compact('produk', 'categories'));
    }

    public function update(Request $request, Product $produk)
    {
        $this->pastikanMilikTokoSendiri($produk);

        $data = $request->validate([
            'nama_produk' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:100',
            'stok' => 'required|integer|min:0',
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('uploads/produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $produk)
    {
        $this->pastikanMilikTokoSendiri($produk);

        if ($produk->orderItems()->exists()) {
            return back()->with('error', 'Produk sudah pernah dipesan sehingga tidak dapat dihapus. Ubah stok menjadi 0 jika tidak dijual lagi.');
        }

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    private function pastikanMilikTokoSendiri(Product $produk): void
    {
        abort_if($produk->store_id !== Auth::user()->store->id, 403, 'Unauthorized access.');
    }
}
