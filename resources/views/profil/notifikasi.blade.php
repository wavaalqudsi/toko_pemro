<x-layout>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="list-group shadow-sm">
                <a href="{{ route('profil.edit') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i>Profil Saya
                </a>
                <a href="{{ route('pesanan.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-bag-check me-2"></i>Pesanan Saya
                </a>
                <a href="{{ route('notifikasi.index') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-bell me-2"></i>Notifikasi
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Notifikasi Status Pesanan</div>
                <div class="list-group list-group-flush">
                    @forelse ($orders as $order)
                        <a href="{{ route('pesanan.show', $order) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">Pesanan {{ $order->kode_pesanan }} ({{ $order->store->nama_toko }})</div>
                                <small class="text-muted">
                                    Status saat ini: <strong>{{ $order->status_label_pembeli }}</strong>
                                    @if ($order->no_resi) &bull; No. Resi: {{ $order->no_resi }} @endif
                                </small>
                            </div>
                            <small class="text-secondary">{{ $order->updated_at->diffForHumans() }}</small>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted">Belum ada notifikasi.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-layout>
