<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::active()->with(['amenities', 'images']);

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('address', 'like', '%' . $request->q . '%')
                    ->orWhere('neighborhood', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('stars')) {
            $query->byStars((int) $request->stars);
        }

        if ($request->filled('max_price')) {
            $query->byMaxPrice((float) $request->max_price);
        }

        if ($request->filled('amenities')) {
            $ids = (array) $request->amenities;
            $query->whereHas('amenities', function ($q) use ($ids) {
                $q->whereIn('amenities.id', $ids);
            }, '>=', count($ids));
        }

        $sort = $request->get('sort', 'rating');
        match ($sort) {
            'price_asc'  => $query->orderBy('price_per_night'),
            'price_desc' => $query->orderByDesc('price_per_night'),
            'newest'     => $query->orderByDesc('created_at'),
            default      => $query->orderByDesc('avg_rating'),
        };

        $hotels    = $query->paginate(12)->withQueryString();
        $amenities = Amenity::all()->groupBy('category');

        return view('public.hotels.index', compact('hotels', 'amenities'));
    }

    public function show(string $slug)
    {
        $hotel = Hotel::active()
            ->where('slug', $slug)
            ->with([
                'amenities',
                'images',
                'rooms',
                'approvedReviews.user',
            ])
            ->firstOrFail();

        $similar = Hotel::active()
            ->where('id', '!=', $hotel->id)
            ->where('city', $hotel->city)
            ->with('images')
            ->orderByDesc('avg_rating')
            ->take(4)
            ->get();

        return view('public.hotels.show', compact('hotel', 'similar'));
    }
}
