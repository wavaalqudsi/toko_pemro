<x-layout>

    <div class="row g-4">
        <div class="col-md-3">
            <x-seller-sidebar />
        </div>

        <div class="col-md-9">
            <h4 class="fw-bold mb-3">Pendapatan Saya</h4>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <div class="card bg-dark text-white shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="small text-white-50">Saldo Aktif (Bisa Ditarik)</div>
                            <div class="h2 fw-bold text-warning my-2">Rp {{ number_format($saldoAktif, 0, ',', '.') }}</div>
                            <form action="{{ route('penjual.pendapatan.tarik') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm fw-semibold px-3">Tarik Dana</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="small text-muted">Dana Belum Dilepas <i class="bi bi-info-circle"></i></div>
                            <div class="h2 fw-bold my-2">Rp {{ number_format($danaBelumDilepas, 0, ',', '.') }}</div>
                            <p class="small text-muted mb-0">
                                Dana ini masih tertahan karena pesanan sedang dalam pengiriman atau belum dikonfirmasi selesai oleh pembeli.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-2">Riwayat Pendapatan & Penarikan</h6>
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Pesanan</th>
                                <th>Pembeli</th>
                                <th>Tanggal Selesai</th>
                                <th class="text-end">Nominal Dilepas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesananSelesai as $order)
                                <tr>
                                    <td class="fw-semibold">{{ $order->kode_pesanan }}</td>
                                    <td>{{ $order->user->nama }}</td>
                                    <td>{{ $order->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="text-end fw-bold text-success">+ Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat penarikan dana bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $pesananSelesai->links() }}
            </div>
        </div>
    </div>
</x-layout>
