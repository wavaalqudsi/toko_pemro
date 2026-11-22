<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'no_hp' => fake()->numerify('08##########'),
            'alamat' => fake()->address(),
            'role' => User::ROLE_PEMBELI,
            'remember_token' => Str::random(10),
        ];
    }

    public function penjual(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_PENJUAL,
        ]);
    }

    public function pembeli(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_PEMBELI,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
