<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-shop text-warning me-1"></i>Seller Centre
    </div>
    <div class="list-group list-group-flush small">
        <div class="list-group-item text-uppercase text-muted fw-bold" style="font-size: 0.72rem;">Dashboard</div>
        <a href="{{ route('penjual.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}">
            Tinjauan Bisnis
        </a>

        <div class="list-group-item text-uppercase text-muted fw-bold" style="font-size: 0.72rem;">Pesanan</div>
        <a href="{{ route('penjual.pesanan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('penjual.pesanan.*') ? 'active' : '' }}">
            Pesanan Saya
        </a>

        <div class="list-group-item text-uppercase text-muted fw-bold" style="font-size: 0.72rem;">Produk</div>
        <a href="{{ route('penjual.produk.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('penjual.produk.index', 'penjual.produk.show', 'penjual.produk.edit') ? 'active' : '' }}">
            Produk Saya
        </a>
        <a href="{{ route('penjual.produk.create') }}" class="list-group-item list-group-item-action text-warning-emphasis fw-semibold {{ request()->routeIs('penjual.produk.create') ? 'active text-white' : '' }}">
            + Tambah Produk Baru
        </a>

        <div class="list-group-item text-uppercase text-muted fw-bold" style="font-size: 0.72rem;">Keuangan</div>
        <a href="{{ route('penjual.pendapatan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('penjual.pendapatan.*') ? 'active' : '' }}">
            Pendapatan Saya
        </a>

        <div class="list-group-item text-uppercase text-muted fw-bold" style="font-size: 0.72rem;">Toko</div>
        <a href="{{ route('penjual.toko.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('penjual.toko.*') ? 'active' : '' }}">
            Pengaturan Toko
        </a>
    </div>
</div>
