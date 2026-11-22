<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading ?? 'NAVA Market' }} - NAVA Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="bi bi-bag-plus me-1"></i>NAVA<span class="fw-normal small">Market</span>
            </a>

            <form action="{{ route('home') }}" method="GET" class="d-flex mx-lg-3 my-2 my-lg-0 flex-grow-1" style="max-width: 520px;">
                <div class="input-group input-group-sm">
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari produk atau toko...">
                    <button class="btn btn-warning" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="d-flex align-items-center gap-3 ms-auto">
                <a href="{{ route('mulai.berjualan') }}" class="text-white text-decoration-none small">
                    Mulai Berjualan
                </a>

                <a href="{{ route('keranjang.index') }}" class="text-white text-decoration-none small position-relative">
                    <i class="bi bi-cart3"></i> Keranjang
                    @if (($jumlahKeranjang ?? 0) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $jumlahKeranjang }}</span>
                    @endif
                </a>

                @auth
                    <a href="{{ route('profil.edit') }}" class="text-white text-decoration-none small">
                        <i class="bi bi-person-circle"></i> Akun Saya ({{ auth()->user()->nama }})
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm py-0">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white text-decoration-none small">
                        <i class="bi bi-person"></i> Masuk / Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="bg-white border-bottom py-2 mb-4">
        <div class="container d-flex flex-wrap align-items-center gap-3 small">
            <a href="{{ route('home') }}" class="text-decoration-none {{ !request('category_id') && request()->routeIs('home') ? 'fw-bold text-dark border-bottom border-2 border-dark pb-1' : 'text-secondary' }}">
                <i class="bi bi-list"></i> Semua Kategori
            </a>
            @foreach ($kategoriNavbar ?? [] as $kat)
                <a href="{{ route('home', ['category_id' => $kat->id]) }}"
                   class="text-decoration-none {{ request('category_id') == $kat->id ? 'fw-bold text-dark border-bottom border-2 border-dark pb-1' : 'text-secondary' }}">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
            <a href="{{ route('bantuan') }}" class="text-decoration-none text-secondary ms-auto">
                <i class="bi bi-question-circle"></i> Bantuan
            </a>
        </div>
    </div>

    @isset($heading)
        <div class="container mb-3">
            <h1 class="h4 fw-bold mb-0">{{ $heading }}</h1>
        </div>
    @endisset

    <main class="container mb-5 flex-grow-1">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            NAVA Market &copy; {{ date('Y') }} &bull;
            <a href="{{ route('bantuan') }}" class="text-muted">Pusat Bantuan</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
