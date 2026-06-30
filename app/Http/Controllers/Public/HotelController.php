<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(Request $request): View
    {
        $query = Hotel::active()->with(['images', 'amenities']);

        // ── Filtros ──────────────────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('neighborhood', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stars')) {
            $query->whereIn('stars', (array) $request->input('stars'));
        }

        if ($request->filled('price_min')) {
            $query->where('price_per_night', '>=', $request->float('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price_per_night', '<=', $request->float('price_max'));
        }

        if ($request->filled('amenities')) {
            $amenityIds = (array) $request->input('amenities');
            $query->whereHas('amenities', function ($q) use ($amenityIds) {
                $q->whereIn('amenities.id', $amenityIds);
            }, '=', count($amenityIds));
        }

        if ($request->filled('neighborhood')) {
            $query->where('neighborhood', $request->string('neighborhood'));
        }

        // ── Ordenação ────────────────────────────────────────────
        $sort = $request->input('sort', 'recommended');
        match($sort) {
            'price_asc'  => $query->orderBy('price_per_night', 'asc'),
            'price_desc' => $query->orderBy('price_per_night', 'desc'),
            'rating'     => $query->orderByDesc('avg_rating'),
            'stars'      => $query->orderByDesc('stars'),
            default      => $query->orderByDesc('is_featured')->orderByDesc('avg_rating'),
        };

        $hotels = $query->paginate(12)->withQueryString();

        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');

        $neighborhoods = Hotel::active()
            ->whereNotNull('neighborhood')
            ->distinct()
            ->pluck('neighborhood');

        return view('public.hotels.index', compact('hotels', 'amenities', 'neighborhoods'));
    }

    public function show(string $slug): View
    {
        $hotel = Hotel::active()
            ->where('slug', $slug)
            ->with([
                'images',
                'amenities',
                'rooms' => fn ($q) => $q->orderBy('price_per_night'),
                'approvedReviews.user',
            ])
            ->firstOrFail();

        $relatedHotels = Hotel::active()
            ->where('id', '!=', $hotel->id)
            ->where('neighborhood', $hotel->neighborhood)
            ->with('images')
            ->take(4)
            ->get();

        $userHasReviewed = auth()->check()
            && $hotel->reviews()->where('user_id', auth()->id())->exists();

        return view('public.hotels.show', compact('hotel', 'relatedHotels', 'userHasReviewed'));
    }
}