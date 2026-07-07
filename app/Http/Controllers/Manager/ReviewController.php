<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $hotel = auth()->user()->hotels()->firstOrFail();

        $reviews = $hotel->reviews()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('manager.reviews.index', compact('hotel', 'reviews'));
    }

    public function reply(Request $request, Review $review)
    {
        // Garante que a avaliação pertence ao hotel do gestor
        $hotel = auth()->user()->hotels()->firstOrFail();
        abort_unless($review->hotel_id === $hotel->id, 403);
        abort_unless($review->isApproved(), 403, 'Só pode responder a avaliações aprovadas.');

        $request->validate([
            'manager_reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'manager_reply'      => $request->manager_reply,
            'manager_replied_at' => now(),
        ]);

        return back()->with('success', 'Resposta publicada com sucesso!');
    }

    public function deleteReply(Review $review)
    {
        $hotel = auth()->user()->hotels()->firstOrFail();
        abort_unless($review->hotel_id === $hotel->id, 403);

        $review->update([
            'manager_reply'      => null,
            'manager_replied_at' => null,
        ]);

        return back()->with('success', 'Resposta removida.');
    }
}