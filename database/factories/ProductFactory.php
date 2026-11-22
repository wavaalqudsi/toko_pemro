<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $namaProduk = [
            'Kaos Polos', 'Kemeja Flanel', 'Celana Chino', 'Sepatu Sneakers', 'Tas Ransel',
            'Headset Bluetooth', 'Mouse Wireless', 'Keyboard Mekanik', 'Powerbank 10000mAh', 'Kabel USB-C',
            'Botol Minum', 'Lampu Tidur', 'Rak Buku Mini', 'Kopi Arabika', 'Keripik Singkong',
            'Sambal Bawang', 'Madu Hutan', 'Novel Fiksi', 'Buku Tulis', 'Pulpen Gel',
        ];

        return [
            'store_id' => Store::factory(),
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'nama_produk' => fake()->randomElement($namaProduk) . ' ' . fake()->bothify('??-###'),
            'deskripsi' => fake()->paragraph(),
            'harga' => fake()->numberBetween(10, 500) * 1000,
            'stok' => fake()->numberBetween(0, 100),
            'foto' => null,
        ];
    }
}
