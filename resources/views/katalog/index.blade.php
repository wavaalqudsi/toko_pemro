<x-layout>
    <x-slot:heading>Katalog Produk</x-slot:heading>

    <form action="{{ route('home') }}" method="GET" class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Cari Produk / Toko</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Nama produk atau toko...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Kategori</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Harga Min</label>
                    <input type="number" name="harga_min" value="{{ request('harga_min') }}" class="form-control form-control-sm" placeholder="Rp 0">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Harga Max</label>
                    <input type="number" name="harga_max" value="{{ request('harga_max') }}" class="form-control form-control-sm" placeholder="Rp ...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Urutkan</label>
                    <select name="urut" class="form-select form-select-sm">
                        <option value="terbaru" @selected(request('urut') == 'terbaru')>Terbaru</option>
                        <option value="terlama" @selected(request('urut') == 'terlama')>Terlama</option>
                        <option value="nama" @selected(request('urut') == 'nama')>Nama A-Z</option>
                        <option value="termurah" @selected(request('urut') == 'termurah')>Harga Termurah</option>
                        <option value="termahal" @selected(request('urut') == 'termahal')>Harga Termahal</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tersedia" value="1" id="chkTersedia" @checked(request('tersedia'))>
                    <label class="form-check-label small" for="chkTersedia">Hanya produk yang stoknya tersedia</label>
                </div>
                <a href="{{ route('home') }}" class="small text-secondary">Reset Filter</a>
            </div>
        </div>
    </form>

    <div class="row g-3">
        @forelse ($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $product->foto_url }}" alt="{{ $product->nama_produk }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary align-self-start mb-1">{{ $product->category->nama_kategori }}</span>
                        <h6 class="card-title mb-1">
                            <a href="{{ route('produk.show', $product) }}" class="text-decoration-none text-dark">
                                {{ $product->nama_produk }}
                            </a>
                        </h6>
                        <p class="text-primary fw-bold mb-1">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        <small class="text-muted mb-2">
                            <i class="bi bi-shop"></i>
                            <a href="{{ route('toko.show', $product->store) }}" class="text-muted text-decoration-none">
                                {{ $product->store->nama_toko }}
                            </a>
                            &bull; Stok: {{ $product->stok }}
                        </small>
                        <a href="{{ route('produk.show', $product) }}" class="btn btn-outline-primary btn-sm mt-auto">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">Belum ada produk yang cocok dengan pencarian.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</x-layout>
