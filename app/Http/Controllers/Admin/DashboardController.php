<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_hotels'        => Hotel::count(),
            'active_hotels'       => Hotel::where('status', 'active')->count(),
            'pending_hotels'      => Hotel::where('status', 'pending')->count(),
            'total_users'         => User::count(),
            'total_managers'      => User::where('role', 'hotel_manager')->count(),
            'total_reviews'       => Review::count(),
            'pending_reviews'     => Review::pending()->count(),
        ];

        $recentHotels = Hotel::with('manager')
            ->latest()
            ->take(5)
            ->get();

        $pendingReviews = Review::pending()
            ->with(['hotel', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentHotels', 'pendingReviews'));
    }
}