<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        $this->call(CategorySeeder::class);

        $katFashion = Category::where('nama_kategori', 'Fashion')->first();
        $katElektronik = Category::where('nama_kategori', 'Elektronik')->first();
        $katRumahTangga = Category::where('nama_kategori', 'Rumah Tangga')->first();
        $katKecantikan = Category::where('nama_kategori', 'Kecantikan')->first();
        $katAksesoris = Category::where('nama_kategori', 'Aksesoris')->first();
        $katFashionWanita = Category::where('nama_kategori', 'Fashion Wanita')->first();

        $penjual = User::factory()->penjual()->create([
            'nama' => 'Admin Nava Official',
            'email' => 'penjual@toko.test',
            'no_hp' => '081299998888',
            'alamat' => 'Jakarta Selatan',
        ]);

        $navaStore = Store::factory()->create([
            'user_id' => $penjual->id,
            'nama_toko' => 'Nava Official Store',
            'deskripsi' => 'Official Store NAVA Market - Pilihan menarik dalam satu tempat.',
            'alamat_toko' => 'Jakarta Selatan',
        ]);

        $lunaBag = Product::create([
            'store_id' => $navaStore->id,
            'category_id' => $katFashionWanita->id,
            'nama_produk' => 'Luna Shoulder Bag',
            'deskripsi' => 'Tas bahu elegan warna beige dengan aksen gold premium. Cocok untuk acara formal maupun kasual.',
            'harga' => 249000,
            'stok' => 452,
        ]);

        $classicWatch = Product::create([
            'store_id' => $navaStore->id,
            'category_id' => $katAksesoris->id,
            'nama_produk' => 'Timeless Classic Watch',
            'deskripsi' => 'Jam tangan klasik dengan tali kulit coklat dan frame gold minimalis.',
            'harga' => 499000,
            'stok' => 45,
        ]);

        $knitSweater = Product::create([
            'store_id' => $navaStore->id,
            'category_id' => $katFashion->id,
            'nama_produk' => 'Knit Sweater Minimal',
            'deskripsi' => 'Sweater rajut lembut dengan potongan minimalis yang nyaman dipakai sehari-hari.',
            'harga' => 189000,
            'stok' => 80,
        ]);

        $deskLamp = Product::create([
            'store_id' => $navaStore->id,
            'category_id' => $katRumahTangga->id,
            'nama_produk' => 'Modern Desk Lamp',
            'deskripsi' => 'Lampu meja belajar dan kerja bergaya modern minimalis dengan cahaya hangat.',
            'harga' => 329000,
            'stok' => 35,
        ]);

        $koreanTop = Product::create([
            'store_id' => $navaStore->id,
            'category_id' => $katFashionWanita->id,
            'nama_produk' => 'Atasan Knit Wanita Korean Top',
            'deskripsi' => 'Atasan rajut wanita gaya Korea, tersedia variasi warna Maroon, Beige, dan Navy.',
            'harga' => 135000,
            'stok' => 124,
        ]);

        Product::factory()->count(7)->create(['store_id' => $navaStore->id]);

        $penjualFortuna = User::factory()->penjual()->create([
            'nama' => 'Owner Fortuna',
            'email' => 'fortuna@toko.test',
        ]);
        $fortunaStore = Store::factory()->create([
            'user_id' => $penjualFortuna->id,
            'nama_toko' => 'Fortuna Store',
            'deskripsi' => 'Pusat aksesoris dan jam tangan original.',
            'alamat_toko' => 'Bandung',
        ]);
        Product::factory()->count(6)->create(['store_id' => $fortunaStore->id]);

        $pembeli = User::factory()->pembeli()->create([
            'nama' => 'Budi Santoso',
            'email' => 'pembeli@toko.test',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Jend. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan, 12190',
        ]);

        $pembeliSiska = User::factory()->pembeli()->create([
            'nama' => 'Siska Amalia',
            'email' => 'siska@toko.test',
            'no_hp' => '081388887777',
            'alamat' => 'Jl. Gatot Subroto No. 12, Jakarta Selatan',
        ]);

        CartItem::create([
            'user_id' => $pembeli->id,
            'product_id' => $lunaBag->id,
            'jumlah' => 1,
        ]);
        CartItem::create([
            'user_id' => $pembeli->id,
            'product_id' => $classicWatch->id,
            'jumlah' => 1,
        ]);

        $order1 = Order::create([
            'user_id' => $pembeli->id,
            'store_id' => $navaStore->id,
            'total_harga' => 514000,
            'alamat_kirim' => 'Budi Santoso | Jl. Jend. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan, 12190',
            'metode_bayar' => 'transfer',
            'status' => Order::STATUS_DIPROSES,
        ]);
        $order1->items()->create([
            'product_id' => $classicWatch->id,
            'jumlah' => 1,
            'harga_satuan' => 499000,
        ]);

        $order2 = Order::create([
            'user_id' => $pembeliSiska->id,
            'store_id' => $navaStore->id,
            'total_harga' => 280000,
            'alamat_kirim' => 'Siska Amalia | Jl. Gatot Subroto No. 12, Jakarta Selatan',
            'metode_bayar' => 'cod',
            'status' => Order::STATUS_DIPROSES,
        ]);
        $order2->items()->create([
            'product_id' => $koreanTop->id,
            'jumlah' => 2,
            'harga_satuan' => 135000,
        ]);

        $order3 = Order::create([
            'user_id' => $pembeli->id,
            'store_id' => $navaStore->id,
            'total_harga' => 499000,
            'alamat_kirim' => $pembeli->alamat,
            'metode_bayar' => 'transfer',
            'status' => Order::STATUS_DIKIRIM,
            'no_resi' => 'JNT202611220003',
        ]);
        $order3->items()->create([
            'product_id' => $classicWatch->id,
            'jumlah' => 1,
            'harga_satuan' => 499000,
        ]);

        $order4 = Order::create([
            'user_id' => $pembeli->id,
            'store_id' => $navaStore->id,
            'total_harga' => 2500000,
            'alamat_kirim' => $pembeli->alamat,
            'metode_bayar' => 'transfer',
            'status' => Order::STATUS_SELESAI,
            'no_resi' => 'JNE8889990001',
        ]);
        $order4->items()->create([
            'product_id' => $classicWatch->id,
            'jumlah' => 5,
            'harga_satuan' => 500000,
        ]);

        $order5 = Order::create([
            'user_id' => $pembeli->id,
            'store_id' => $navaStore->id,
            'total_harga' => 249000,
            'alamat_kirim' => $pembeli->alamat,
            'metode_bayar' => 'transfer',
            'status' => Order::STATUS_MENUNGGU_PEMBAYARAN,
        ]);
        $order5->items()->create([
            'product_id' => $lunaBag->id,
            'jumlah' => 1,
            'harga_satuan' => 249000,
        ]);
    }
}
