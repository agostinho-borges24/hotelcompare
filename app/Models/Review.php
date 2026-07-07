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
        'manager_reply',
        'manager_replied_at',
    ];

    protected function casts(): array
    {
        return [
            'stay_date'          => 'datetime',
            'manager_replied_at' => 'datetime',
            'rating'             => 'integer',
            'rating_cleanliness' => 'integer',
            'rating_service'     => 'integer',
            'rating_location'    => 'integer',
            'rating_value'       => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            $review->hotel->recalculateRating();
        });

        static::deleted(function (Review $review) {
            $review->hotel->recalculateRating();
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function hasManagerReply(): bool
    {
        return !empty($this->manager_reply);
    }

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}