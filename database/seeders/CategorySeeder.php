<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {

        $kategori = [
            'Fashion',
            'Elektronik',
            'Rumah Tangga',
            'Kecantikan',
            'Aksesoris',
            'Fashion Wanita',
        ];

        foreach ($kategori as $nama) {
            Category::firstOrCreate(['nama_kategori' => $nama]);
        }
    }
}
