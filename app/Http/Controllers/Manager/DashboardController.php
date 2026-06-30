<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $hotel = auth()->user()->hotels()->with(['rooms', 'images'])->first();

        if (! $hotel) {
            return view('manager.dashboard', ['hotel' => null]);
        }

        $stats = [
            'total_rooms'      => $hotel->rooms()->count(),
            'available_rooms'  => $hotel->rooms()->available()->count(),
            'total_reviews'    => $hotel->total_reviews,
            'avg_rating'       => $hotel->avg_rating,
            'pending_reviews'  => $hotel->reviews()->pending()->count(),
        ];

        $recentReviews = $hotel->reviews()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', compact('hotel', 'stats', 'recentReviews'));
    }
}