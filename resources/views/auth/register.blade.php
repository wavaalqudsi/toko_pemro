<x-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white p-0">
                    <div class="row g-0 text-center">
                        <div class="col-6 py-2 border-bottom">
                            <a href="{{ route('login') }}" class="text-decoration-none text-secondary d-block">Masuk</a>
                        </div>
                        <div class="col-6 py-2 border-bottom border-2 border-dark fw-bold">Daftar</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h4 class="text-center fw-bold mb-1">Buat Akun Baru</h4>
                    <p class="text-center text-muted small mb-4">Gabung sekarang dan nikmati promonya</p>

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Cth: Budi Santoso" class="form-control @error('nama') is-invalid @enderror" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Aktif</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Cth: budi@email.com" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" placeholder="Buat password (min. 8 karakter)" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning fw-semibold">Daftar Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
