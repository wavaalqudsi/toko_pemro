<x-layout>

    <x-slot:heading>Keranjang Belanja</x-slot:heading>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($cartItems->isEmpty())
        <div class="card shadow-sm text-center p-5">
            <h5 class="text-muted mb-3">Keranjang belanja Anda masih kosong.</h5>
            <div>
                <a href="{{ route('home') }}" class="btn btn-dark">Mulai Belanja</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <span class="fw-semibold">Daftar Produk ({{ $cartItems->count() }})</span>
                    <form action="{{ route('keranjang.destroy.selected') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none btn-sm p-0">Hapus Semua</button>
                    </form>
                </div>

                @foreach ($cartItems as $item)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $item->product->foto_url }}" alt="" class="rounded border" style="width: 64px; height: 64px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('produk.show', $item->product) }}" class="text-decoration-none text-dark">
                                            {{ $item->product->nama_produk }}
                                        </a>
                                    </h6>
                                    <div class="small text-muted mb-1">{{ $item->product->store->nama_toko }} &bull; Stok: {{ $item->product->stok }}</div>
                                    <div class="fw-bold text-warning-emphasis">Rp {{ number_format($item->product->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">

                                <form action="{{ route('keranjang.update', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="aksi" value="kurang">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-2">-</button>
                                </form>

                                <span class="px-2 fw-semibold">{{ $item->jumlah }}</span>

                                <form action="{{ route('keranjang.update', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="aksi" value="tambah">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-2">+</button>
                                </form>

                                <form action="{{ route('keranjang.destroy', $item) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Belanja</h6>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Total Harga ({{ $totalBarang }} barang)</span>
                            <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3">
                            <span class="text-muted">Total Diskon</span>
                            <span class="text-success">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold mb-3">
                            <span>Total Bayar</span>
                            <span class="text-warning-emphasis fs-5">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout.create') }}" class="btn btn-dark w-100">
                            Lanjut ke Checkout ({{ $totalBarang }})
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layout>
