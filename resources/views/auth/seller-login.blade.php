<x-layout>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm overflow-hidden">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="fw-bold mb-1">Seller Centre</h4>
                    <small class="text-white-50">Kelola toko Anda dengan mudah</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <input type="hidden" name="seller_login" value="1">

                        <div class="mb-3">
                            <label class="form-label">Email / No. HP Seller</label>
                            <input type="text" name="email" value="{{ old('email') }}" placeholder="Masukkan email seller" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" placeholder="Masukkan password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Masuk ke Seller Centre</button>
                        </div>
                    </form>

                    <div class="text-center mt-3 small">
                        Baru mulai berjualan? <a href="{{ route('register') }}" class="fw-semibold text-dark">Daftar Toko</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
