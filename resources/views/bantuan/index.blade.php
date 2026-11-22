<x-layout>

    <div class="text-center my-4">
        <h2 class="fw-bold mb-3">Halo, ada yang bisa kami bantu?</h2>
        <form action="{{ route('bantuan') }}" method="GET" class="col-md-6 mx-auto">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="cari" value="{{ $cari }}" class="form-control" placeholder="Cari topik bantuan (pengiriman, pembayaran, retur, akun)...">
                <button class="btn btn-dark" type="submit">Cari</button>
            </div>
        </form>
    </div>

    <div class="row g-3 my-4">
        @forelse ($topikBantuan as $topik)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm text-center p-3">
                    <div class="fs-2 mb-2"><i class="bi {{ $topik['ikon'] }}"></i></div>
                    <h6 class="fw-bold mb-1">{{ $topik['judul'] }}</h6>
                    <p class="text-muted small mb-3">{{ $topik['subjudul'] }}</p>
                    <div class="text-start small border-top pt-2">
                        @foreach ($topik['faq'] as $tanya => $jawab)
                            <div class="mb-2">
                                <strong>{{ $tanya }}</strong>
                                <div class="text-muted">{{ $jawab }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">Topik bantuan tidak ditemukan.</div>
            </div>
        @endforelse
    </div>
</x-layout>
