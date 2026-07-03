<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Hotel;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    const MAX_COMPARE = 3;

    public function index(Request $request)
    {
        $ids = array_filter(explode(',', $request->get('ids', '')));

        // Suporta também ids[] vindos do formulário
        if (empty($ids) && $request->filled('ids')) {
            $ids = (array) $request->ids;
        }

        $ids = array_slice(array_filter($ids), 0, self::MAX_COMPARE);

        $hotels = collect();

        if (!empty($ids)) {
            $hotels = Hotel::active()
                ->whereIn('id', $ids)
                ->with(['amenities', 'rooms', 'approvedReviews'])
                ->get();
        }

        $allAmenities = Amenity::orderBy('category')->orderBy('name')->get();

        $availableHotels = Hotel::active()
            ->select('id', 'name', 'slug', 'stars', 'price_per_night')
            ->orderBy('name')
            ->get();

        return view('public.hotels.compare', compact(
            'hotels',
            'allAmenities',
            'availableHotels',
        ));
    }
}