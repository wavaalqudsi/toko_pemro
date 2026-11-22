<x-layout>
    <x-slot:heading>Detail Pesanan #{{ $order->id }}</x-slot:heading>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Daftar Barang ({{ $order->store->nama_toko }})</span>
                    <span class="badge bg-primary">{{ $order->status_label }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->nama_produk }}</td>
                                    <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Harga:</th>
                                <th class="text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-bold">Informasi Pengiriman & Pembayaran</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Metode Bayar:</strong> {{ strtoupper($order->metode_bayar) }}</p>
                    <p class="mb-2"><strong>Status:</strong> {{ $order->status_label }}</p>
                    <p class="mb-2"><strong>No. Resi:</strong> {{ $order->no_resi ?? 'Belum dikirim' }}</p>
                    <p class="mb-2"><strong>Alamat Kirim:</strong><br>{{ $order->alamat_kirim }}</p>

                    @if ($order->bukti_bayar_url)
                        <hr>
                        <p class="fw-semibold mb-1">Bukti Pembayaran:</p>
                        <img src="{{ $order->bukti_bayar_url }}" alt="Bukti Bayar" class="img-fluid rounded border mb-2">
                    @endif

                    @if ($order->bisaUploadBukti())
                        <hr>
                        <form action="{{ route('pesanan.bukti', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="form-label fw-semibold small">Upload Bukti Transfer (JPG/PNG, maks 2MB)</label>
                            <input type="file" name="bukti_bayar" accept="image/*" class="form-control form-control-sm mb-2 @error('bukti_bayar') is-invalid @enderror" required>
                            @error('bukti_bayar') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror
                            <button type="submit" class="btn btn-primary btn-sm w-100">Upload Bukti Bayar</button>
                        </form>
                    @endif

                    @if ($order->bisaDiselesaikan())
                        <hr>
                        <form action="{{ route('pesanan.selesai', $order) }}" method="POST" onsubmit="return confirm('Apakah barang sudah Anda terima dengan baik?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">Pesanan Diterima (Selesai)</button>
                        </form>
                    @endif

                    @if ($order->bisaDibatalkanPembeli())
                        <hr>
                        <form action="{{ route('pesanan.batal', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Batalkan Pesanan</button>
                        </form>
                    @endif
                </div>
            </div>

            <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali ke Daftar Pesanan</a>
        </div>
    </div>
</x-layout>
