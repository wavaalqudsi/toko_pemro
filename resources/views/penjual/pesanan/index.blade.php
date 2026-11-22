<x-layout>

    <div class="row g-4">
        <div class="col-md-3">
            <x-seller-sidebar />
        </div>

        <div class="col-md-9">
            <h4 class="fw-bold mb-3">Pesanan Saya</h4>

            <div class="card shadow-sm mb-3">
                <div class="card-body py-2 d-flex flex-wrap gap-4">
                    <a href="{{ route('penjual.pesanan.index') }}"
                       class="text-decoration-none py-1 {{ !request('status') ? 'fw-bold text-dark border-bottom border-2 border-dark' : 'text-secondary' }}">
                        Semua
                    </a>
                    @foreach ($tabStatus as $kode => $label)
                        <a href="{{ route('penjual.pesanan.index', ['status' => $kode]) }}"
                           class="text-decoration-none py-1 {{ request('status') === $kode ? 'fw-bold text-dark border-bottom border-2 border-dark' : 'text-secondary' }}">
                            {{ $label }}
                            @if (($jumlahPerTab[$kode] ?? 0) > 0)
                                <span class="badge bg-danger rounded-pill">{{ $jumlahPerTab[$kode] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            @forelse ($orders as $order)
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $order->user->nama }}</strong>
                            <span class="text-muted small ms-2">{{ $order->kode_pesanan }}</span>
                        </div>
                        <span class="badge bg-danger">{{ strtoupper($order->status_label_penjual) }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                @foreach ($order->items as $item)
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <img src="{{ $item->product->foto_url }}" alt="" class="rounded border" style="width: 56px; height: 56px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ $item->product->nama_produk }}</div>
                                            <div class="small text-muted">x{{ $item->jumlah }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="small text-muted">Total Belanja</div>
                                <div class="h5 fw-bold text-warning-emphasis mb-0">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="small text-muted">
                                <i class="bi bi-truck"></i>
                                @if ($order->no_resi)
                                    Resi: <strong>{{ $order->no_resi }}</strong>
                                @else
                                    Kurir: Reguler - J&T Express
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('penjual.pesanan.show', $order) }}" class="btn btn-outline-secondary btn-sm">Detail / Hubungi Pembeli</a>

                                @if ($order->bisaDikirim())
                                    <form action="{{ route('penjual.pesanan.kirim', $order) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-dark btn-sm">Atur Pengiriman</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary text-center">Tidak ada pesanan pada tab ini.</div>
            @endforelse

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-layout>
