<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::with('manager')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $hotels = $query->paginate(15)->withQueryString();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $managers  = User::where('role', 'hotel_manager')->where('is_active', true)->get();
        $amenities = Amenity::all()->groupBy('category');
        return view('admin.hotels.create', compact('managers', 'amenities'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateHotel($request);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('hotels/covers', 'public');
        }

        $amenityIds = $validated['amenities'] ?? [];
        unset($validated['amenities']);

        $hotel = Hotel::create($validated);
        $hotel->amenities()->sync($amenityIds);

        return redirect()->route('admin.hoteis.index')
            ->with('success', 'Hotel criado com sucesso!');
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['manager', 'amenities', 'rooms', 'images', 'approvedReviews.user']);
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        $managers  = User::where('role', 'hotel_manager')->where('is_active', true)->get();
        $amenities = Amenity::all()->groupBy('category');
        $hotel->load('amenities');
        return view('admin.hotels.edit', compact('hotel', 'managers', 'amenities'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $this->validateHotel($request, $hotel->id);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('hotels/covers', 'public');
        }

        $amenityIds = $validated['amenities'] ?? [];
        unset($validated['amenities']);

        $hotel->update($validated);
        $hotel->amenities()->sync($amenityIds);

        return redirect()->route('admin.hoteis.index')
            ->with('success', 'Hotel actualizado com sucesso!');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hoteis.index')
            ->with('success', 'Hotel removido.');
    }

    public function updateStatus(Request $request, Hotel $hotel)
    {
        $request->validate([
            'status' => 'required|in:pending,active,suspended',
        ]);

        $hotel->update(['status' => $request->status]);

        $label = match($request->status) {
            'active'    => 'activado',
            'suspended' => 'suspenso',
            default     => 'colocado como pendente',
        };

        return back()->with('success', "Hotel {$label} com sucesso!");
    }

    private function validateHotel(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'user_id'        => 'required|exists:users,id',
            'name'           => 'required|string|max:150',
            'description'    => 'nullable|string|max:3000',
            'address'        => 'required|string|max:255',
            'neighborhood'   => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150',
            'website'        => 'nullable|url|max:255',
            'stars'          => 'required|integer|min:1|max:5',
            'price_per_night'=> 'required|numeric|min:0',
            'status'         => 'required|in:pending,active,suspended',
            'is_featured'    => 'boolean',
            'cover_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'amenities'      => 'nullable|array',
            'amenities.*'    => 'exists:amenities,id',
        ]);
    }
}