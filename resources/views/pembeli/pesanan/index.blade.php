<x-layout>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="list-group shadow-sm">
                <a href="{{ route('profil.edit') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i>Profil Saya
                </a>
                <a href="{{ route('pesanan.index') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-bag-check me-2"></i>Pesanan Saya
                </a>
                <a href="{{ route('notifikasi.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-bell me-2"></i>Notifikasi
                </a>
            </div>
        </div>

        <div class="col-md-9">

            <div class="card shadow-sm mb-3">
                <div class="card-body py-2 d-flex flex-wrap gap-3">
                    <a href="{{ route('pesanan.index') }}"
                       class="text-decoration-none py-1 {{ !request('status') ? 'fw-bold text-dark border-bottom border-2 border-dark' : 'text-secondary' }}">
                        Semua
                    </a>
                    @foreach ($tabStatus as $kode => $label)
                        <a href="{{ route('pesanan.index', ['status' => $kode]) }}"
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <i class="bi bi-shop me-1"></i><strong>{{ $order->store->nama_toko }}</strong>
                            <span class="text-muted small ms-2">{{ $order->kode_pesanan }}</span>
                        </div>
                        <span class="badge bg-warning text-dark">{{ strtoupper($order->status_label_pembeli) }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($order->items as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $item->product->foto_url }}" alt="" class="rounded border" style="width: 52px; height: 52px; object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold">{{ $item->product->nama_produk }}</div>
                                        <div class="small text-muted">x{{ $item->jumlah }}</div>
                                    </div>
                                </div>
                                <div class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <span class="text-muted small">Total Pesanan:</span>
                                <span class="fw-bold text-warning-emphasis fs-5 ms-1">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('pesanan.show', $order) }}" class="btn btn-outline-secondary btn-sm">Detail Pesanan</a>
                                @if ($order->bisaDibatalkanPembeli())
                                    <form action="{{ route('pesanan.batal', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-dark btn-sm">Batalkan Pesanan</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary text-center">Belum ada pesanan pada tab ini.</div>
            @endforelse

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-layout>
