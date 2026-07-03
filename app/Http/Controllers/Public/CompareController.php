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
        // ids pode vir como array (ids[]=1&ids[]=2) ou string (ids=1,2)
        $raw = $request->get('ids', []);

        if (is_array($raw)) {
            $ids = $raw;
        } else {
            $ids = explode(',', $raw);
        }

        // Limpa valores vazios e limita a 3
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