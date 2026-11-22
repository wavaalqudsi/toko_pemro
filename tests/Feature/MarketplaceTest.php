<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_katalog_publik_dan_bantuan_berjalan_tanpa_lazy_loading(): void
    {
        $category = Category::factory()->create(['nama_kategori' => 'Fashion']);
        $store = Store::factory()->create(['nama_toko' => 'Nava Official Store']);
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'nama_produk' => 'Luna Shoulder Bag',
            'harga' => 249000,
            'stok' => 10,
        ]);

        $this->get(route('home', ['cari' => 'Luna', 'category_id' => $category->id, 'urut' => 'termurah']))
            ->assertOk()
            ->assertSee('Luna Shoulder Bag');

        $this->get(route('produk.show', $product))
            ->assertOk()
            ->assertSee('Luna Shoulder Bag')
            ->assertSee('Beli Sekarang');

        $this->get(route('toko.show', $store))
            ->assertOk()
            ->assertSee('Nava Official Store');

        $this->get(route('bantuan', ['cari' => 'Pengiriman']))
            ->assertOk()
            ->assertSee('Halo, ada yang bisa kami bantu?');
    }

    public function test_pembeli_bisa_mulai_berjualan_buka_toko_dan_crud_produk_serta_cek_pendapatan(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create(['nama_kategori' => 'Aksesoris']);
        $user = User::factory()->pembeli()->create();

        $this->actingAs($user)
            ->get(route('penjual.dashboard'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('mulai.berjualan'))
            ->assertRedirect(route('penjual.toko.create'));

        $this->actingAs($user)
            ->post(route('penjual.toko.store'), [
                'nama_toko' => 'Nava Official Store',
                'kota_asal' => 'Jakarta Selatan',
                'deskripsi' => 'Toko resmi NAVA Market',
            ])
            ->assertRedirect(route('penjual.dashboard'));

        $this->assertEquals(User::ROLE_PENJUAL, $user->fresh()->role);
        $this->assertDatabaseHas('stores', ['user_id' => $user->id, 'nama_toko' => 'Nava Official Store', 'alamat_toko' => 'Jakarta Selatan']);

        $user->refresh();
        $foto = UploadedFile::fake()->image('watch.jpg');

        $this->actingAs($user)
            ->post(route('penjual.produk.store'), [
                'nama_produk' => 'Timeless Classic Watch',
                'category_id' => $category->id,
                'deskripsi' => 'Jam tangan klasik',
                'harga' => 499000,
                'stok' => 45,
                'foto' => $foto,
            ])
            ->assertRedirect(route('penjual.produk.index'));

        $product = Product::first();
        $this->assertNotNull($product->foto);
        Storage::disk('public')->assertExists($product->foto);

        $this->actingAs($user)
            ->get(route('penjual.pendapatan.index'))
            ->assertOk()
            ->assertSee('Saldo Aktif (Bisa Ditarik)');
    }

    public function test_alur_lengkap_pembeli_checkout_ongkir_dan_opsi_bayar_hingga_selesai(): void
    {
        Storage::fake('public');

        $store = Store::factory()->create();
        $penjual = $store->user;
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'harga' => 499000,
            'stok' => 10,
        ]);

        $pembeli = User::factory()->pembeli()->create(['no_hp' => '081234567890']);

        $this->actingAs($pembeli)
            ->post(route('keranjang.store'), [
                'product_id' => $product->id,
                'jumlah' => 1,
            ])
            ->assertRedirect(route('keranjang.index'));

        $cartItem = $pembeli->cartItems()->first();

        $this->actingAs($pembeli)
            ->put(route('keranjang.update', $cartItem), ['aksi' => 'tambah'])
            ->assertRedirect(route('keranjang.index'));

        $this->assertEquals(2, $cartItem->fresh()->jumlah);

        $this->actingAs($pembeli)
            ->post(route('checkout.store'), [
                'nama_penerima' => 'Budi Santoso',
                'alamat_kirim' => 'Jl. Jend. Sudirman No. 45, Jakarta Selatan',
                'opsi_pengiriman' => 'reguler',
                'metode_bayar' => 'bca_va',
            ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals(Order::STATUS_MENUNGGU_PEMBAYARAN, $order->status);

        $this->assertEquals(1013000, (float) $order->total_harga);
        $this->assertEquals(8, $product->fresh()->stok);

        $this->actingAs($pembeli)
            ->post(route('pesanan.bukti', $order), [
                'bukti_bayar' => UploadedFile::fake()->image('bukti.png'),
            ])
            ->assertRedirect(route('pesanan.show', $order));

        $this->assertEquals(Order::STATUS_MENUNGGU_KONFIRMASI, $order->fresh()->status);

        $this->actingAs($penjual)
            ->patch(route('penjual.pesanan.konfirmasi', $order))
            ->assertRedirect(route('penjual.pesanan.show', $order));

        $this->assertEquals(Order::STATUS_DIPROSES, $order->fresh()->status);

        $this->actingAs($penjual)
            ->patch(route('penjual.pesanan.kirim', $order))
            ->assertRedirect(route('penjual.pesanan.show', $order));

        $this->assertEquals(Order::STATUS_DIKIRIM, $order->fresh()->status);
        $this->assertNotNull($order->fresh()->no_resi);

        $this->actingAs($pembeli)
            ->patch(route('pesanan.selesai', $order))
            ->assertRedirect(route('pesanan.show', $order));

        $this->assertEquals(Order::STATUS_SELESAI, $order->fresh()->status);
    }
}
