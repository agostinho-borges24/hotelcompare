<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'type',
        'description',
        'price_per_night',
        'max_guests',
        'beds',
        'has_ac',
        'has_tv',
        'has_wifi',
        'has_private_bathroom',
        'is_available',
        'total_units',
        'available_units',
        'cover_image',
    ];

    protected function casts(): array
    {
        return [
            'price_per_night'     => 'float',
            'has_ac'              => 'boolean',
            'has_tv'              => 'boolean',
            'has_wifi'            => 'boolean',
            'has_private_bathroom'=> 'boolean',
            'is_available'        => 'boolean',
        ];
    }

    // ─── Scopes ─────────────────────────────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('available_units', '>', 0);
    }

    // ─── Helpers ────────────────────────────────────────────────────
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'single'        => 'Individual',
            'double'        => 'Duplo',
            'suite'         => 'Suite',
            'family'        => 'Familiar',
            'presidential'  => 'Presidencial',
            default         => ucfirst($this->type),
        };
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return asset('images/room-placeholder.jpg');
    }

    // ─── Relações ───────────────────────────────────────────────────
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}