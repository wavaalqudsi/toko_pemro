<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class KatalogController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'harga_min' => 'nullable|numeric|min:0',
            'harga_max' => 'nullable|numeric|min:0',
        ]);

        $products = Product::with(['store', 'category'])
            ->cari($request->input('cari'))
            ->filter($request->only(['category_id', 'harga_min', 'harga_max', 'tersedia']))
            ->urutkan($request->input('urut'))
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('nama_kategori')->get();

        return view('katalog.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['store', 'category']);

        $produkTerkait = Product::with('store')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('katalog.show', compact('product', 'produkTerkait'));
    }
}
