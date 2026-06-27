<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'category',
    ];

    // ─── Relações ───────────────────────────────────────────────────
    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_amenity');
    }
}