<x-layout>

    <div class="card shadow-sm col-md-7 mx-auto">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3">Informasi Toko Baru</h4>
            <hr>

            <form action="{{ route('penjual.toko.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Toko *</label>
                    <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" placeholder="Cth: Nava Official Store" class="form-control @error('nama_toko') is-invalid @enderror" required>
                    @error('nama_toko') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kota Asal Pengiriman *</label>
                    <select name="alamat_toko" class="form-select @error('alamat_toko') is-invalid @enderror">
                        @foreach ($daftarKota as $kota)
                            <option value="{{ $kota }}" @selected(old('alamat_toko') == $kota)>{{ $kota }}</option>
                        @endforeach
                    </select>
                    @error('alamat_toko') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="3" placeholder="Ceritakan tentang toko dan produk yang dijual..." class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-dark px-4">Buat Toko Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
