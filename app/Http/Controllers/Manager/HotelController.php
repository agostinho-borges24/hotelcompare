<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    private function getHotel()
    {
        return auth()->user()->hotels()->firstOrFail();
    }

    public function index()
    {
        $hotel = $this->getHotel()->load(['amenities', 'images', 'rooms']);
        return view('manager.hotel.index', compact('hotel'));
    }

    public function edit()
    {
        $hotel     = $this->getHotel()->load(['amenities', 'images']);
        $amenities = Amenity::all()->groupBy('category');
        return view('manager.hotel.edit', compact('hotel', 'amenities'));
    }

    public function update(Request $request)
    {
        $hotel = $this->getHotel();

        $validated = $request->validate([
            'name'            => 'required|string|max:150',
            'description'     => 'nullable|string|max:3000',
            'address'         => 'required|string|max:255',
            'neighborhood'    => 'nullable|string|max:100',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:150',
            'website'         => 'nullable|url|max:255',
            'stars'           => 'required|integer|min:1|max:5',
            'price_per_night' => 'required|numeric|min:0',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'amenities'       => 'nullable|array',
            'amenities.*'     => 'exists:amenities,id',
        ]);

        $amenityIds = $validated['amenities'] ?? [];
        unset($validated['amenities']);

        $hotel->update($validated);
        $hotel->amenities()->sync($amenityIds);

        return redirect()->route('manager.hotel.index')
            ->with('success', 'Informações do hotel actualizadas com sucesso!');
    }

    public function uploadImages(Request $request)
    {
        $hotel = $this->getHotel();

        $request->validate([
            'images'   => 'required|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store("hotels/{$hotel->id}/gallery", 'public');

            HotelImage::create([
                'hotel_id' => $hotel->id,
                'path'     => $path,
                'order'    => $hotel->images()->max('order') + 1,
            ]);
        }

        return back()->with('success', 'Imagens carregadas com sucesso!');
    }

    public function deleteImage(HotelImage $image)
    {
        $hotel = $this->getHotel();
        abort_unless($image->hotel_id === $hotel->id, 403);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Imagem removida.');
    }
}