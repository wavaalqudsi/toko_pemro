<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\Pembeli\CheckoutController;
use App\Http\Controllers\Pembeli\KeranjangController;
use App\Http\Controllers\Pembeli\PesananController as PembeliPesananController;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboardController;
use App\Http\Controllers\Penjual\PendapatanController as PenjualPendapatanController;
use App\Http\Controllers\Penjual\PesananController as PenjualPesananController;
use App\Http\Controllers\Penjual\ProdukController as PenjualProdukController;
use App\Http\Controllers\Penjual\TokoController as PenjualTokoController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TokoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KatalogController::class, 'index'])->name('home');
Route::get('/produk/{product}', [KatalogController::class, 'show'])->name('produk.show');
Route::get('/toko/{store}', [TokoController::class, 'show'])->name('toko.show');
Route::get('/bantuan', [BantuanController::class, 'index'])->name('bantuan');

Route::get('/mulai-berjualan', [AuthController::class, 'mulaiBerjualan'])->name('mulai.berjualan');
Route::get('/seller/login', [AuthController::class, 'showSellerLogin'])->name('seller.login');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/notifikasi', [ProfilController::class, 'notifikasi'])->name('notifikasi.index');
});

Route::middleware(['auth', 'role:pembeli,penjual'])->group(function () {

    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::put('/keranjang/{cartItem}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{cartItem}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::delete('/keranjang', [KeranjangController::class, 'destroySelected'])->name('keranjang.destroy.selected');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pesanan', [PembeliPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [PembeliPesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{order}/bukti-bayar', [PembeliPesananController::class, 'uploadBukti'])->name('pesanan.bukti');
    Route::patch('/pesanan/{order}/batal', [PembeliPesananController::class, 'batal'])->name('pesanan.batal');
    Route::patch('/pesanan/{order}/selesai', [PembeliPesananController::class, 'selesai'])->name('pesanan.selesai');
});

Route::prefix('penjual')->name('penjual.')->group(function () {

    Route::middleware('auth')->group(function () {
        Route::get('/toko/buat', [PenjualTokoController::class, 'create'])->name('toko.create');
        Route::post('/toko', [PenjualTokoController::class, 'store'])->name('toko.store');
    });

    Route::middleware(['auth', 'role:penjual', 'punya.toko'])->group(function () {

        Route::get('/dashboard', [PenjualDashboardController::class, 'index'])->name('dashboard');

        Route::get('/toko/edit', [PenjualTokoController::class, 'edit'])->name('toko.edit');
        Route::put('/toko', [PenjualTokoController::class, 'update'])->name('toko.update');

        Route::resource('produk', PenjualProdukController::class)
            ->parameters(['produk' => 'produk']);

        Route::get('/pesanan', [PenjualPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{order}', [PenjualPesananController::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{order}/konfirmasi-bayar', [PenjualPesananController::class, 'konfirmasiBayar'])->name('pesanan.konfirmasi');
        Route::patch('/pesanan/{order}/tolak-bayar', [PenjualPesananController::class, 'tolakBayar'])->name('pesanan.tolak');
        Route::patch('/pesanan/{order}/kirim', [PenjualPesananController::class, 'kirim'])->name('pesanan.kirim');
        Route::patch('/pesanan/{order}/batal', [PenjualPesananController::class, 'batal'])->name('pesanan.batal');

        Route::get('/pendapatan', [PenjualPendapatanController::class, 'index'])->name('pendapatan.index');
        Route::post('/pendapatan/tarik', [PenjualPendapatanController::class, 'tarikDana'])->name('pendapatan.tarik');
    });
});
