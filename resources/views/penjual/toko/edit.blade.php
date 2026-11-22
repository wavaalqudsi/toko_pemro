<x-layout>
    <div class="row g-4">
        <div class="col-md-3">
            <x-seller-sidebar />
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Pengaturan Toko - {{ $store->nama_toko }}</h5>
                        <a href="{{ route('toko.show', $store) }}" class="btn btn-outline-secondary btn-sm">Lihat Halaman Publik Toko</a>
                    </div>
                    <hr>

                    <form action="{{ route('penjual.toko.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Toko *</label>
                            <input type="text" name="nama_toko" value="{{ old('nama_toko', $store->nama_toko) }}" class="form-control @error('nama_toko') is-invalid @enderror" required>
                            @error('nama_toko') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kota Asal Pengiriman / Alamat Toko *</label>
                            <input type="text" name="alamat_toko" list="listKota" value="{{ old('alamat_toko', $store->alamat_toko) }}" class="form-control @error('alamat_toko') is-invalid @enderror">
                            <datalist id="listKota">
                                @foreach ($daftarKota as $kota)
                                    <option value="{{ $kota }}">
                                @endforeach
                            </datalist>
                            @error('alamat_toko') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $store->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
