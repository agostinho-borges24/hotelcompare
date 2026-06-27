<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'address',
        'neighborhood',
        'city',
        'province',
        'country',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'stars',
        'price_per_night',
        'cover_image',
        'status',
        'is_featured',
        'avg_rating',
        'total_reviews',
    ];

    protected function casts(): array
    {
        return [
            'latitude'       => 'float',
            'longitude'      => 'float',
            'price_per_night'=> 'float',
            'avg_rating'     => 'float',
            'is_featured'    => 'boolean',
            'stars'          => 'integer',
            'total_reviews'  => 'integer',
        ];
    }

    // ─── Auto-gera slug ao criar ────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Hotel $hotel) {
            if (empty($hotel->slug)) {
                $hotel->slug = Str::slug($hotel->name) . '-' . Str::random(5);
            }
        });
    }

    // ─── Scopes ─────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByStars($query, int $stars)
    {
        return $query->where('stars', $stars);
    }

    public function scopeByMaxPrice($query, float $price)
    {
        return $query->where('price_per_night', '<=', $price);
    }

    // ─── Helpers ────────────────────────────────────────────────────
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return asset('images/hotel-placeholder.jpg');
    }

    public function getStarsLabelAttribute(): string
    {
        return str_repeat('★', $this->stars) . str_repeat('☆', 5 - $this->stars);
    }

    // Recalcula e guarda a média de avaliações
    public function recalculateRating(): void
    {
        $approved = $this->reviews()->where('status', 'approved');
        $this->avg_rating   = round($approved->avg('rating') ?? 0, 2);
        $this->total_reviews = $approved->count();
        $this->saveQuietly();
    }

    // ─── Relações ───────────────────────────────────────────────────
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenity');
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class)->orderBy('order');
    }

    public function coverImages()
    {
        return $this->hasMany(HotelImage::class)->where('is_cover', true);
    }
}