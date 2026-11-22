<x-layout>
    <x-slot:heading>Edit Produk</x-slot:heading>

    <div class="card shadow-sm col-md-8 mx-auto">
        <div class="card-body p-4">
            <form action="{{ route('penjual.produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" class="form-control @error('nama_produk') is-invalid @enderror" required>
                        @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $produk->category_id) == $category->id)>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga', (int) $produk->harga) }}" min="100" class="form-control @error('harga') is-invalid @enderror" required>
                        @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" min="0" class="form-control @error('stok') is-invalid @enderror" required>
                        @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ganti Foto (JPG/PNG, maks 2MB)</label>
                        <input type="file" name="foto" accept="image/*" class="form-control @error('foto') is-invalid @enderror">
                        @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <img src="{{ $produk->foto_url }}" alt="Preview" class="rounded border mt-2" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('penjual.produk.index') }}" class="btn btn-outline-secondary">&larr; Kembali</a>
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
