<x-layout>

    <div class="row g-4">
        <div class="col-md-3">
            <x-seller-sidebar />
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h4 class="fw-bold mb-0">Produk Saya</h4>
                <a href="{{ route('penjual.produk.create') }}" class="btn btn-dark btn-sm">
                    + Tambah Produk Baru
                </a>
            </div>

            <form action="{{ route('penjual.produk.index') }}" method="GET" class="d-flex flex-wrap gap-2 mb-3">
                <input type="text" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" style="width: 200px;" placeholder="Cari nama produk...">
                <select name="category_id" class="form-select form-select-sm" style="width: 170px;">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->nama_kategori }}</option>
                    @endforeach
                </select>
                <select name="urut" class="form-select form-select-sm" style="width: 140px;">
                    <option value="terbaru" @selected(request('urut') == 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected(request('urut') == 'terlama')>Terlama</option>
                    <option value="nama" @selected(request('urut') == 'nama')>Nama A-Z</option>
                    <option value="termurah" @selected(request('urut') == 'termurah')>Termurah</option>
                    <option value="termahal" @selected(request('urut') == 'termahal')>Termahal</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Terapkan</button>
                <a href="{{ route('penjual.produk.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </form>

            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Informasi Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $products->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $product->foto_url }}" alt="" class="rounded border" style="width: 48px; height: 48px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold">{{ $product->nama_produk }}</div>
                                                <small class="text-muted">{{ $product->category->nama_kategori }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                    <td>{{ $product->stok }}</td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('penjual.produk.edit', $product) }}" class="text-warning-emphasis text-decoration-none fw-semibold">Edit</a>
                                            <form action="{{ route('penjual.produk.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 fw-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-layout>
