<?php

namespace App\Http\Controllers\Manager;

use App\Events\RoomAvailabilityUpdated;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    private function getHotel()
    {
        return auth()->user()->hotels()->firstOrFail();
    }

    public function index()
    {
        $hotel = $this->getHotel();
        $rooms = $hotel->rooms()->orderBy('type')->get();
        return view('manager.rooms.index', compact('hotel', 'rooms'));
    }

    public function create()
    {
        $hotel = $this->getHotel();
        return view('manager.rooms.create', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel     = $this->getHotel();
        $validated = $this->validateRoom($request);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store("hotels/{$hotel->id}/rooms", 'public');
        }

        $validated['available_units'] = $validated['total_units'];
        $hotel->rooms()->create($validated);

        return redirect()->route('manager.quartos.index')
            ->with('success', 'Quarto criado com sucesso!');
    }

    public function edit(Room $quarto)
    {
        abort_unless($quarto->hotel_id === $this->getHotel()->id, 403);
        return view('manager.rooms.edit', ['room' => $quarto]);
    }

    public function update(Request $request, Room $quarto)
    {
        abort_unless($quarto->hotel_id === $this->getHotel()->id, 403);
        $validated = $this->validateRoom($request, $quarto->id);

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($quarto->cover_image);
            $validated['cover_image'] = $request->file('cover_image')
                ->store("hotels/{$quarto->hotel_id}/rooms", 'public');
        }

        $quarto->update($validated);

        return redirect()->route('manager.quartos.index')
            ->with('success', 'Quarto actualizado com sucesso!');
    }

    public function destroy(Room $quarto)
    {
        abort_unless($quarto->hotel_id === $this->getHotel()->id, 403);

        if ($quarto->cover_image) {
            Storage::disk('public')->delete($quarto->cover_image);
        }

        $quarto->delete();

        return redirect()->route('manager.quartos.index')
            ->with('success', 'Quarto removido.');
    }

    // ─── Disponibilidade em tempo real via Reverb ────────────────────
    public function toggleAvailability(Request $request, Room $room)
    {
        abort_unless($room->hotel_id === $this->getHotel()->id, 403);

        $request->validate([
            'available_units' => 'required|integer|min:0|max:' . $room->total_units,
        ]);

        $room->update([
            'available_units' => $request->available_units,
            'is_available'    => $request->available_units > 0,
        ]);

        // Dispara evento WebSocket para actualização em tempo real
        broadcast(new RoomAvailabilityUpdated($room))->toOthers();

        return response()->json([
            'success'         => true,
            'is_available'    => $room->is_available,
            'available_units' => $room->available_units,
        ]);
    }

    private function validateRoom(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'                => 'required|string|max:100',
            'type'                => 'required|in:single,double,suite,family,presidential',
            'description'         => 'nullable|string|max:1000',
            'price_per_night'     => 'required|numeric|min:0',
            'max_guests'          => 'required|integer|min:1|max:20',
            'beds'                => 'required|integer|min:1|max:10',
            'has_ac'              => 'boolean',
            'has_tv'              => 'boolean',
            'has_wifi'            => 'boolean',
            'has_private_bathroom'=> 'boolean',
            'total_units'         => 'required|integer|min:1',
            'cover_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }
}