<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompareController extends Controller
{
    public function index(Request $request): View
    {
        $ids = collect(explode(',', (string) $request->input('ids', '')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->take(4) // máximo de 4 hotéis em comparação
            ->values();

        $hotels = Hotel::active()
            ->whereIn('id', $ids)
            ->with(['amenities', 'rooms', 'images'])
            ->get()
            ->sortBy(fn ($hotel) => $ids->search($hotel->id))
            ->values();

        // Lista de todas as comodidades para a tabela comparativa
        $allAmenities = $hotels->pluck('amenities')->flatten()->unique('id')->sortBy('name');

        // Hotéis sugeridos para adicionar à comparação
        $suggestions = Hotel::active()
            ->whereNotIn('id', $ids)
            ->with('images')
            ->orderByDesc('avg_rating')
            ->take(6)
            ->get();

        return view('public.hotels.compare', compact('hotels', 'allAmenities', 'suggestions'));
    }
}