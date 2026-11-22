<x-layout>

    <div class="row g-4">
        <div class="col-md-3">
            <x-seller-sidebar />
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Tinjauan Bisnis</h4>
                <span class="badge bg-dark fs-6">{{ $store->nama_toko }}</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Pesanan Baru</div>
                            <div class="h3 fw-bold my-1">{{ $ringkasan['pesanan_baru'] }}</div>
                            <small class="text-success">Total pesanan: {{ $ringkasan['total_pesanan'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Total Pendapatan</div>
                            <div class="h3 fw-bold my-1">Rp {{ number_format($ringkasan['total_pendapatan'], 0, ',', '.') }}</div>
                            <small class="text-success">Hari ini: Rp {{ number_format($ringkasan['pendapatan_hari_ini'], 0, ',', '.') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Total Kunjungan / Produk</div>
                            <div class="h3 fw-bold my-1">{{ $ringkasan['total_kunjungan'] }}</div>
                            <small class="text-muted">{{ $ringkasan['total_produk'] }} produk aktif (habis: {{ $ringkasan['stok_habis'] }})</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Perlu Diproses</h6>
                    <hr class="mt-0">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 fw-bold text-warning mb-0">{{ $ringkasan['belum_dibayar'] }}</div>
                            <small class="text-muted">Belum Dibayar</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 fw-bold text-danger mb-0">{{ $ringkasan['perlu_dikirim'] }}</div>
                            <small class="text-muted">Perlu Dikirim</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 fw-bold text-dark mb-0">{{ $ringkasan['pengajuan_retur'] }}</div>
                            <small class="text-muted">Pengajuan Retur</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Pesanan Terbaru</span>
                    <a href="{{ route('penjual.pesanan.index') }}" class="small text-decoration-none">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesananTerbaru as $order)
                                <tr>
                                    <td class="fw-bold">{{ $order->kode_invoice }}</td>
                                    <td>{{ $order->user->nama }}</td>
                                    <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $order->status_label_penjual }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('penjual.pesanan.show', $order) }}" class="btn btn-outline-dark btn-sm">Kelola</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout>
