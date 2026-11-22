<x-layout>
    <x-slot:heading>Kelola Pesanan #{{ $order->id }}</x-slot:heading>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Item Pesanan</span>
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
                <div class="card-header bg-white fw-bold">Data Pembeli & Tindakan</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Pembeli:</strong> {{ $order->user->nama }}</p>
                    <p class="mb-1"><strong>No. HP:</strong> {{ $order->user->no_hp ?? '-' }}</p>
                    <p class="mb-2"><strong>Alamat Kirim:</strong><br>{{ $order->alamat_kirim }}</p>
                    <hr>
                    <p class="mb-1"><strong>Metode Bayar:</strong> {{ strtoupper($order->metode_bayar) }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ $order->status_label }}</p>
                    <p class="mb-2"><strong>No. Resi:</strong> {{ $order->no_resi ?? '-' }}</p>

                    @if ($order->bukti_bayar_url)
                        <hr>
                        <p class="fw-semibold mb-1">Bukti Transfer Pembeli:</p>
                        <img src="{{ $order->bukti_bayar_url }}" alt="Bukti Bayar" class="img-fluid rounded border mb-2">
                    @endif

                    @if ($order->bisaDikonfirmasi())
                        <hr>
                        <div class="d-grid gap-2">
                            <form action="{{ route('penjual.pesanan.konfirmasi', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100">Konfirmasi Pembayaran Valid</button>
                            </form>
                            <form action="{{ route('penjual.pesanan.tolak', $order) }}" method="POST" onsubmit="return confirm('Tolak bukti bayar ini dan minta pembeli upload ulang?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning w-100">Tolak Bukti Bayar</button>
                            </form>
                        </div>
                    @endif

                    @if ($order->bisaDikirim())
                        <hr>
                        <form action="{{ route('penjual.pesanan.kirim', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label class="form-label fw-semibold small">Nomor Resi Pengiriman</label>
                            <input type="text" name="no_resi" value="{{ old('no_resi') }}" placeholder="Contoh: JNE123456789" class="form-control form-control-sm mb-2 @error('no_resi') is-invalid @enderror" required>
                            @error('no_resi') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror
                            <button type="submit" class="btn btn-primary btn-sm w-100">Kirim Pesanan</button>
                        </form>
                    @endif

                    @if ($order->bisaDibatalkanPenjual())
                        <hr>
                        <form action="{{ route('penjual.pesanan.batal', $order) }}" method="POST" onsubmit="return confirm('Yakin batalkan pesanan ini? Stok produk akan dikembalikan.')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Batalkan Pesanan</button>
                        </form>
                    @endif
                </div>
            </div>

            <a href="{{ route('penjual.pesanan.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali ke Pesanan Masuk</a>
        </div>
    </div>
</x-layout>
