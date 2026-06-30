<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredHotels = Hotel::active()
            ->featured()
            ->with(['images', 'amenities'])
            ->orderByDesc('avg_rating')
            ->take(6)
            ->get();

        $topRatedHotels = Hotel::active()
            ->with(['images'])
            ->where('total_reviews', '>', 0)
            ->orderByDesc('avg_rating')
            ->take(8)
            ->get();

        $totalHotels = Hotel::active()->count();
        $totalReviews = Hotel::active()->sum('total_reviews');

        return view('public.home', compact(
            'featuredHotels',
            'topRatedHotels',
            'totalHotels',
            'totalReviews'
        ));
    }
}