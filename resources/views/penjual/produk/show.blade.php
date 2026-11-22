<x-layout>
    <x-slot:heading>Detail Produk Toko</x-slot:heading>

    <div class="card shadow-sm col-md-8 mx-auto">
        <div class="card-body p-4">
            <div class="d-flex gap-4 flex-wrap">
                <img src="{{ $produk->foto_url }}" alt="{{ $produk->nama_produk }}" class="rounded border" style="width: 160px; height: 160px; object-fit: cover;">
                <div>
                    <span class="badge bg-secondary mb-1">{{ $produk->category->nama_kategori }}</span>
                    <h4 class="fw-bold mb-1">{{ $produk->nama_produk }}</h4>
                    <h5 class="text-primary fw-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h5>
                    <p class="mb-1">Stok: <strong>{{ $produk->stok }}</strong></p>
                    <small class="text-muted">Dibuat: {{ $produk->created_at->format('d M Y, H:i') }}</small>
                </div>
            </div>
            <hr>
            <h6>Deskripsi:</h6>
            <p style="white-space: pre-line;">{{ $produk->deskripsi ?? '-' }}</p>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('penjual.produk.index') }}" class="btn btn-outline-secondary">&larr; Kembali</a>
                <a href="{{ route('penjual.produk.edit', $produk) }}" class="btn btn-warning">Edit Produk</a>
            </div>
        </div>
    </div>
</x-layout>
