<x-layout>

    <x-slot:heading>Checkout Pesanan</x-slot:heading>

    <form action="{{ route('checkout.store') }}" method="POST" onsubmit="return confirm('Konfirmasi Pesanan: Apakah Anda yakin ingin melakukan pembayaran?')">
        @csrf
        @foreach ($selectedItems as $idItem)
            <input type="hidden" name="items[]" value="{{ $idItem }}">
        @endforeach

        <div class="row g-4">
            <div class="col-lg-7">

                <div class="card shadow-sm mb-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-warning me-1"></i>Alamat Pengiriman</h6>

                        <div class="mb-3">
                            <label class="form-label small">Nama Penerima</label>
                            <input type="text" name="nama_penerima" value="{{ old('nama_penerima', $user->nama) }}" class="form-control @error('nama_penerima') is-invalid @enderror">
                            @error('nama_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label small">Alamat Lengkap</label>
                            <textarea name="alamat_kirim" rows="3" class="form-control @error('alamat_kirim') is-invalid @enderror" required>{{ old('alamat_kirim', $user->alamat) }}</textarea>
                            @error('alamat_kirim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-truck text-warning me-1"></i>Opsi Pengiriman</h6>
                        <select name="opsi_pengiriman" class="form-select">
                            @foreach ($opsiPengiriman as $kode => $opsi)
                                <option value="{{ $kode }}" @selected(old('opsi_pengiriman') == $kode)>
                                    {{ $opsi['nama'] }} - Rp {{ number_format($opsi['ongkir'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-credit-card text-warning me-1"></i>Metode Pembayaran</h6>
                        @foreach ($metodeBayar as $kode => $label)
                            <div class="form-check border rounded p-3 mb-2">
                                <input class="form-check-input" type="radio" name="metode_bayar" id="metode_{{ $kode }}" value="{{ $kode }}" @checked(old('metode_bayar', 'transfer') == $kode)>
                                <label class="form-check-label fw-semibold" for="metode_{{ $kode }}">
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach
                        @error('metode_bayar') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>

                        @foreach ($cartItems as $item)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                <img src="{{ $item->product->foto_url }}" alt="" class="rounded border" style="width: 52px; height: 52px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $item->product->nama_produk }}</div>
                                    <div class="text-muted small">{{ $item->jumlah }} barang</div>
                                    <div class="fw-bold text-warning-emphasis small">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Subtotal Produk</span>
                            <span>Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Ongkos Kirim ({{ $keranjangPerToko->count() }} toko)</span>
                            <span>Rp {{ number_format($ongkirDefault, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3">
                            <span class="text-success">Diskon Voucher</span>
                            <span class="text-success">- Rp {{ number_format($diskonVoucher, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-warning fw-semibold w-100 py-2">Buat Pesanan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layout>
