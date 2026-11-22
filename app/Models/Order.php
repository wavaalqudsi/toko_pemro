<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{

    public const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    public const STATUS_MENUNGGU_KONFIRMASI = 'menunggu_konfirmasi';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_DIKIRIM = 'dikirim';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUS_LABELS = [
        self::STATUS_MENUNGGU_PEMBAYARAN => 'Belum Bayar',
        self::STATUS_MENUNGGU_KONFIRMASI => 'Menunggu Konfirmasi',
        self::STATUS_DIPROSES => 'Sedang Dikemas / Perlu Dikirim',
        self::STATUS_DIKIRIM => 'Sedang Dikirim',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    public const STATUS_LABELS_PEMBELI = [
        self::STATUS_MENUNGGU_PEMBAYARAN => 'Belum Bayar',
        self::STATUS_MENUNGGU_KONFIRMASI => 'Menunggu Konfirmasi',
        self::STATUS_DIPROSES => 'Sedang Dikemas',
        self::STATUS_DIKIRIM => 'Dikirim',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    public const STATUS_LABELS_PENJUAL = [
        self::STATUS_MENUNGGU_PEMBAYARAN => 'Belum Dibayar',
        self::STATUS_MENUNGGU_KONFIRMASI => 'Perlu Konfirmasi Bayar',
        self::STATUS_DIPROSES => 'Perlu Dikirim',
        self::STATUS_DIKIRIM => 'Sedang Dikirim',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    public const OPSI_PENGIRIMAN = [
        'reguler' => ['nama' => 'Reguler - J&T Express (Estimasi 2-3 Hari)', 'ongkir' => 15000],
        'ekspres' => ['nama' => 'Ekspres - JNE YES (Estimasi 1-2 Hari)', 'ongkir' => 25000],
        'instant' => ['nama' => 'Instant - GoSend (Hari yang sama)', 'ongkir' => 35000],
    ];

    public const METODE_BAYAR = [
        'transfer' => 'Transfer Bank / BCA Virtual Account',
        'bca_va'   => 'BCA Virtual Account',
        'gopay'    => 'Dompet Digital (GoPay / OVO)',
        'cod'      => 'COD (Bayar di Tempat)',
    ];

    protected $fillable = [
        'user_id',
        'store_id',
        'total_harga',
        'alamat_kirim',
        'metode_bayar',
        'bukti_bayar',
        'status',
        'no_resi',
    ];

    protected function casts(): array
    {
        return [
            'total_harga' => 'decimal:2',
        ];
    }

    protected $appends = [
        'kode_pesanan',
        'kode_invoice',
        'status_label',
        'status_label_pembeli',
        'status_label_penjual',
        'bukti_bayar_url',
        'subtotal_produk',
        'ongkir',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getKodePesananAttribute(): string
    {
        $tanggal = ($this->created_at ?? now())->format('Ymd');

        return '#ORD-' . $tanggal . '-' . str_pad((string) $this->id, 2, '0', STR_PAD_LEFT);
    }

    public function getKodeInvoiceAttribute(): string
    {
        return 'INV-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusLabelPembeliAttribute(): string
    {
        return self::STATUS_LABELS_PEMBELI[$this->status] ?? $this->status;
    }

    public function getStatusLabelPenjualAttribute(): string
    {
        return self::STATUS_LABELS_PENJUAL[$this->status] ?? $this->status;
    }

    public function getBuktiBayarUrlAttribute(): ?string
    {
        if ($this->bukti_bayar && Storage::disk('public')->exists($this->bukti_bayar)) {
            return asset('storage/' . $this->bukti_bayar);
        }

        return null;
    }

    public function getSubtotalProdukAttribute(): float
    {
        if ($this->relationLoaded('items')) {
            return (float) $this->items->sum(fn ($item) => $item->subtotal);
        }

        return (float) $this->total_harga;
    }

    public function getOngkirAttribute(): float
    {
        if ($this->relationLoaded('items')) {
            return max(0, (float) $this->total_harga - $this->subtotal_produk);
        }

        return 0;
    }

    public function bisaUploadBukti(): bool
    {
        return $this->metode_bayar === 'transfer'
            && in_array($this->status, [self::STATUS_MENUNGGU_PEMBAYARAN, self::STATUS_MENUNGGU_KONFIRMASI]);
    }

    public function bisaDibatalkanPembeli(): bool
    {
        return in_array($this->status, [
            self::STATUS_MENUNGGU_PEMBAYARAN,
            self::STATUS_MENUNGGU_KONFIRMASI,
            self::STATUS_DIPROSES,
        ]);
    }

    public function bisaDibatalkanPenjual(): bool
    {
        return in_array($this->status, [
            self::STATUS_MENUNGGU_PEMBAYARAN,
            self::STATUS_MENUNGGU_KONFIRMASI,
            self::STATUS_DIPROSES,
        ]);
    }

    public function bisaDikonfirmasi(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_KONFIRMASI;
    }

    public function bisaDikirim(): bool
    {
        return $this->status === self::STATUS_DIPROSES;
    }

    public function bisaDiselesaikan(): bool
    {
        return $this->status === self::STATUS_DIKIRIM;
    }

    public function scopeStatus(Builder $query, ?string $status): void
    {
        if (! $status) {
            return;
        }

        $mapAlias = [
            'belum_bayar'   => [self::STATUS_MENUNGGU_PEMBAYARAN, self::STATUS_MENUNGGU_KONFIRMASI],
            'dikemas'       => [self::STATUS_DIPROSES],
            'perlu_dikirim' => [self::STATUS_DIPROSES, self::STATUS_MENUNGGU_KONFIRMASI],
        ];

        if (isset($mapAlias[$status])) {
            $query->whereIn('status', $mapAlias[$status]);
        } elseif (array_key_exists($status, self::STATUS_LABELS)) {
            $query->where('status', $status);
        }
    }
}
