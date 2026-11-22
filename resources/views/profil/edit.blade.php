<x-layout>

    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning text-white fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $user->nama }}</h5>
                    <small class="text-muted"><i class="bi bi-award text-warning"></i> Member Silver &bull; {{ ucfirst($user->role) }}</small>
                </div>
            </div>
            <a href="{{ route('profil.edit') }}" class="btn btn-outline-secondary btn-sm">Edit Profil</a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="list-group shadow-sm">
                <a href="{{ route('profil.edit') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person me-2"></i>Profil Saya
                </a>
                <a href="{{ route('pesanan.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-bag-check me-2"></i>Pesanan Saya
                </a>
                <a href="{{ route('notifikasi.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-bell me-2"></i>Notifikasi</span>
                    @if (($jumlahNotifikasi ?? 0) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $jumlahNotifikasi }}</span>
                    @endif
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Profil Saya</h5>
                    <p class="text-muted small mb-3">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
                    <hr>

                    <form method="POST" action="{{ route('profil.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="form-control @error('no_hp') is-invalid @enderror">
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman Utama</label>
                            <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>
                        <p class="small text-muted mb-2">Kosongkan password jika tidak ingin mengubahnya.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark">Simpan Profil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
