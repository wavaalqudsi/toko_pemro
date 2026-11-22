<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BantuanController extends Controller
{

    public function index(Request $request)
    {
        $topikBantuan = [
            [
                'slug' => 'pengiriman',
                'judul' => 'Pengiriman',
                'subjudul' => 'Cek status dan info kurir',
                'ikon' => 'bi-truck',
                'faq' => [
                    'Bagaimana cara melacak nomor resi pesanan saya?' => 'Buka menu Akun Saya > Pesanan Saya, lalu klik Detail Pesanan untuk melihat nomor resi pengiriman dari kurir.',
                    'Berapa lama estimasi pengiriman barang?' => 'Reguler (J&T Express) 2-3 hari, Ekspres (JNE YES) 1-2 hari, dan Instant (GoSend) tiba di hari yang sama.',
                ],
            ],
            [
                'slug' => 'pembayaran',
                'judul' => 'Pembayaran',
                'subjudul' => 'Cara bayar dan kendala',
                'ikon' => 'bi-credit-card',
                'faq' => [
                    'Metode pembayaran apa saja yang tersedia?' => 'Tersedia Transfer Bank (BCA Virtual Account), Dompet Digital (GoPay / OVO), serta COD (Bayar di Tempat).',
                    'Bagaimana cara mengunggah bukti pembayaran?' => 'Setelah checkout dengan metode Transfer Bank, buka halaman Detail Pesanan lalu unggah foto bukti transfer Anda.',
                ],
            ],
            [
                'slug' => 'pengembalian',
                'judul' => 'Pengembalian',
                'subjudul' => 'Proses retur & dana',
                'ikon' => 'bi-box-seam',
                'faq' => [
                    'Bagaimana jika pesanan dibatalkan?' => 'Jika pesanan dibatalkan sebelum dikirim, stok produk otomatis dikembalikan dan dana Anda aman.',
                ],
            ],
            [
                'slug' => 'keamanan',
                'judul' => 'Akun Keamanan',
                'subjudul' => 'Kata sandi dan profil',
                'ikon' => 'bi-shield-lock',
                'faq' => [
                    'Bagaimana cara mengubah kata sandi dan alamat?' => 'Buka menu Akun Saya > Profil Saya, perbarui informasi yang diinginkan, lalu klik Simpan Profil.',
                ],
            ],
        ];

        $cari = $request->input('cari');
        if ($cari) {
            $topikBantuan = array_values(array_filter($topikBantuan, function ($topik) use ($cari) {
                if (stripos($topik['judul'], $cari) !== false || stripos($topik['subjudul'], $cari) !== false) {
                    return true;
                }
                foreach ($topik['faq'] as $tanya => $jawab) {
                    if (stripos($tanya, $cari) !== false || stripos($jawab, $cari) !== false) {
                        return true;
                    }
                }

                return false;
            }));
        }

        return view('bantuan.index', compact('topikBantuan', 'cari'));
    }
}
