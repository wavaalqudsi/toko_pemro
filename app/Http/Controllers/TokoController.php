<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;

class TokoController extends Controller
{

    public function show(Request $request, Store $store)
    {
        $store->load('user')->loadCount('products');

        $products = $store->products()
            ->with('category')
            ->cari($request->input('cari'))
            ->filter($request->only(['category_id']))
            ->urutkan($request->input('urut'))
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('nama_kategori')->get();

        return view('toko.show', compact('store', 'products', 'categories'));
    }
}
