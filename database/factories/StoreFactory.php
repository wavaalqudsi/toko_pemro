<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    public function definition(): array
    {
        return [

            'user_id' => User::factory()->penjual(),
            'nama_toko' => fake()->company() . ' Store',
            'deskripsi' => fake()->sentence(12),
            'alamat_toko' => fake()->address(),
        ];
    }
}
