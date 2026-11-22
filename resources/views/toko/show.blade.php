<x-layout>
    <x-slot:heading>{{ $store->nama_toko }}</x-slot:heading>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-wrap">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-shop me-1"></i>{{ $store->nama_toko }}</h5>
                    <p class="text-muted mb-1">{{ $store->deskripsi ?? 'Tidak ada deskripsi toko.' }}</p>
                    <small class="text-secondary">
                        <i class="bi bi-geo-alt"></i> {{ $store->alamat_toko ?? '-' }} &bull;
                        Pemilik: {{ $store->user->nama }} &bull;
                        Total Produk: {{ $store->products_count }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('toko.show', $store) }}" method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari produk di toko ini...">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="urut" class="form-select form-select-sm">
                <option value="terbaru" @selected(request('urut') == 'terbaru')>Terbaru</option>
                <option value="termurah" @selected(request('urut') == 'termurah')>Termurah</option>
                <option value="termahal" @selected(request('urut') == 'termahal')>Termahal</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            <a href="{{ route('toko.show', $store) }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </form>

    <div class="row g-3">
        @forelse ($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $product->foto_url }}" alt="{{ $product->nama_produk }}" class="card-img-top" style="height: 170px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary align-self-start mb-1">{{ $product->category->nama_kategori }}</span>
                        <h6 class="card-title mb-1">
                            <a href="{{ route('produk.show', $product) }}" class="text-decoration-none text-dark">{{ $product->nama_produk }}</a>
                        </h6>
                        <p class="text-primary fw-bold mb-2">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        <a href="{{ route('produk.show', $product) }}" class="btn btn-outline-primary btn-sm mt-auto">Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">Toko ini belum memiliki produk.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</x-layout>
