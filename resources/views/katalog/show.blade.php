<x-layout>

    <div class="small text-muted mb-3">
        <a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a> &gt;
        <a href="{{ route('home', ['category_id' => $product->category_id]) }}" class="text-muted text-decoration-none">{{ $product->category->nama_kategori }}</a> &gt;
        <span class="text-dark fw-semibold">{{ $product->nama_produk }}</span>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-5">
                    <img src="{{ $product->foto_url }}" alt="{{ $product->nama_produk }}" class="img-fluid rounded border w-100" style="max-height: 360px; object-fit: cover;">
                </div>
                <div class="col-md-7">
                    <h3 class="fw-bold mb-1">{{ $product->nama_produk }}</h3>
                    <div class="small text-muted mb-3">
                        Toko: <a href="{{ route('toko.show', $product->store) }}" class="fw-semibold text-decoration-none">{{ $product->store->nama_toko }}</a>
                        &bull; Kategori: <span class="badge bg-secondary">{{ $product->category->nama_kategori }}</span>
                    </div>

                    <div class="p-3 bg-light rounded border mb-3">
                        <span class="badge bg-dark mb-1">HARGA</span>
                        <h3 class="text-warning-emphasis fw-bold mb-0">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
                    </div>

                    <p class="small mb-2"><i class="bi bi-truck text-success me-1"></i> <strong>Pengiriman:</strong> Estimasi tiba 2 - 3 hari</p>
                    <p class="small mb-3"><i class="bi bi-shield-check text-danger me-1"></i> <strong>Jaminan:</strong> Bebas Pengembalian &bull; COD-Cek Dulu</p>

                    <h6>Deskripsi Produk:</h6>
                    <p class="text-muted small" style="white-space: pre-line;">{{ $product->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                    <hr>
                    @auth
                        @if ($product->stok > 0)
                            <form action="{{ route('keranjang.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <label class="small text-muted">Kuantitas</label>
                                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" max="{{ $product->stok }}" class="form-control form-control-sm text-center @error('jumlah') is-invalid @enderror" style="width: 90px;" required>
                                    <span class="small text-muted">Tersisa {{ $product->stok }} buah</span>
                                </div>
                                @error('jumlah')
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror

                                <div class="d-flex gap-2">
                                    <button type="submit" name="beli_sekarang" value="0" class="btn btn-outline-warning text-dark fw-semibold">
                                        <i class="bi bi-cart-plus"></i> Masukkan Keranjang
                                    </button>
                                    <button type="submit" name="beli_sekarang" value="1" class="btn btn-dark fw-semibold px-4">
                                        Beli Sekarang
                                    </button>
                                </div>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Stok Habis</button>
                        @endif
                    @else
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-dark"><i class="bi bi-cart-plus"></i> Masukkan Keranjang</a>
                            <a href="{{ route('login') }}" class="btn btn-dark">Beli Sekarang</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    @if ($produkTerkait->isNotEmpty())
        <h5 class="fw-bold mb-3">Pilihan Lainnya</h5>
        <div class="row g-3">
            @foreach ($produkTerkait as $item)
                <div class="col-6 col-md-3">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $item->foto_url }}" alt="{{ $item->nama_produk }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title mb-1">
                                <a href="{{ route('produk.show', $item) }}" class="text-decoration-none text-dark">{{ $item->nama_produk }}</a>
                            </h6>
                            <p class="fw-bold text-warning-emphasis mb-0">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layout>
