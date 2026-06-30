<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $utilizador)
    {
        $utilizador->load('hotels.rooms', 'reviews.hotel');
        return view('admin.users.show', ['user' => $utilizador]);
    }

    public function edit(User $utilizador)
    {
        return view('admin.users.edit', ['user' => $utilizador]);
    }

    public function update(Request $request, User $utilizador)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $utilizador->id,
            'role'  => 'required|in:admin,hotel_manager,guest',
            'phone' => 'nullable|string|max:20',
        ]);

        $utilizador->update($validated);

        return redirect()->route('admin.utilizadores.index')
            ->with('success', 'Utilizador actualizado com sucesso!');
    }

    public function toggleActive(User $utilizador)
    {
        // Não permite desactivar o próprio admin
        abort_if($utilizador->id === auth()->id(), 403, 'Não pode suspender a sua própria conta.');

        $utilizador->update(['is_active' => ! $utilizador->is_active]);

        $estado = $utilizador->is_active ? 'activado' : 'suspenso';
        return back()->with('success', "Utilizador {$estado} com sucesso!");
    }
}