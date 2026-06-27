<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'rating_cleanliness',
        'rating_service',
        'rating_location',
        'rating_value',
        'status',
        'stay_date',
    ];

    protected function casts(): array
    {
        return [
            'stay_date' => 'datetime',
            'rating'    => 'integer',
            'rating_cleanliness' => 'integer',
            'rating_service'     => 'integer',
            'rating_location'    => 'integer',
            'rating_value'       => 'integer',
        ];
    }

    // ─── Após guardar ou apagar, recalcula a média do hotel ─────────
    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            $review->hotel->recalculateRating();
        });

        static::deleted(function (Review $review) {
            $review->hotel->recalculateRating();
        });
    }

    // ─── Scopes ─────────────────────────────────────────────────────
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ─── Helpers ────────────────────────────────────────────────────
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    // ─── Relações ───────────────────────────────────────────────────
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}