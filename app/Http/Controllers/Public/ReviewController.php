<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Hotel $hotel)
    {
        $request->validate([
            'rating'              => ['required', 'integer', 'min:1', 'max:5'],
            'title'                => ['nullable', 'string', 'max:120'],
            'comment'              => ['nullable', 'string', 'max:2000'],
            'rating_cleanliness'   => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_service'       => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_location'      => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_value'         => ['nullable', 'integer', 'min:1', 'max:5'],
            'stay_date'            => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        // Um utilizador só pode avaliar um hotel uma vez
        $existing = Review::where('hotel_id', $hotel->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['rating' => 'Já avaliaste este hotel anteriormente.']);
        }

        Review::create([
            'hotel_id'            => $hotel->id,
            'user_id'              => $request->user()->id,
            'rating'                => $request->rating,
            'title'                 => $request->title,
            'comment'               => $request->comment,
            'rating_cleanliness'    => $request->rating_cleanliness,
            'rating_service'        => $request->rating_service,
            'rating_location'       => $request->rating_location,
            'rating_value'          => $request->rating_value,
            'stay_date'             => $request->stay_date,
            'status'                => 'pending', // aguarda aprovação do admin
        ]);

        return back()->with('success', 'A tua avaliação foi enviada e está aguardando aprovação.');
    }

    public function destroy(Hotel $hotel, Review $review)
    {
        // Apenas o autor pode apagar a sua própria avaliação
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Avaliação removida com sucesso.');
    }
}