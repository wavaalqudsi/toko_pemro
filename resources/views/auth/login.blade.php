<x-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white p-0">
                    <div class="row g-0 text-center">
                        <div class="col-6 py-2 border-bottom border-2 border-dark fw-bold">Masuk</div>
                        <div class="col-6 py-2 border-bottom">
                            <a href="{{ route('register') }}" class="text-decoration-none text-secondary d-block">Daftar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h4 class="text-center fw-bold mb-1">Selamat Datang Kembali</h4>
                    <p class="text-center text-muted small mb-4">Masuk untuk melanjutkan belanja</p>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email atau No. Handphone</label>
                            <input type="text" name="email" value="{{ old('email') }}" placeholder="Masukkan email / no HP" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" placeholder="Masukkan password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Masuk</button>
                        </div>
                    </form>

                    <div class="text-center mt-3 small">
                        Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar Sekarang</a>
                    </div>
                    <hr>
                    <div class="small text-muted">
                        <strong>Akun Demo Seeder (password: <code>password</code>):</strong><br>
                        Penjual (Nava Official Store): <code>penjual@toko.test</code><br>
                        Pembeli (Budi Santoso): <code>pembeli@toko.test</code> atau No HP <code>081234567890</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
