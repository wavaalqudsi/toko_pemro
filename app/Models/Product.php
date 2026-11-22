<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'stok' => 'integer',
        ];
    }

    protected $appends = ['foto_url'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        return asset('images/no-image.svg');
    }

    public function scopeCari(Builder $query, ?string $kata): void
    {
        $query->when($kata, function ($query, $kata) {
            $query->where(function ($q) use ($kata) {
                $q->where('nama_produk', 'like', "%{$kata}%")
                  ->orWhereHas('store', fn ($s) =>
                      $s->where('nama_toko', 'like', "%{$kata}%"));
            });
        });
    }

    public function scopeFilter(Builder $query, array $filter): void
    {
        $query
            ->when($filter['category_id'] ?? null, fn ($q, $id) =>
                $q->where('category_id', $id))
            ->when($filter['harga_min'] ?? null, fn ($q, $min) =>
                $q->where('harga', '>=', $min))
            ->when($filter['harga_max'] ?? null, fn ($q, $max) =>
                $q->where('harga', '<=', $max))
            ->when($filter['tersedia'] ?? null, fn ($q) =>
                $q->where('stok', '>', 0));
    }

    public function scopeUrutkan(Builder $query, ?string $urut): void
    {
        match ($urut) {
            'terlama'  => $query->oldest(),
            'nama'     => $query->orderBy('nama_produk'),
            'termurah' => $query->orderBy('harga'),
            'termahal' => $query->orderByDesc('harga'),
            default    => $query->latest(),
        };
    }
}
