<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'path',
        'caption',
        'is_cover',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
            'order'    => 'integer',
        ];
    }

    // ─── Helpers ────────────────────────────────────────────────────
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    // ─── Relações ───────────────────────────────────────────────────
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}