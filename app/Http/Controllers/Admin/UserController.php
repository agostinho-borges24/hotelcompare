<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,hotel_manager,guest',
            'phone'    => 'nullable|string|max:20',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('admin.utilizadores.index')
            ->with('success', 'Utilizador criado com sucesso!');
    }

    public function show(User $utilizador)
    {
        $utilizador->load('hotels.rooms', 'reviews.hotel');
        return view('admin.users.show', ['user' => $utilizador]);
    }

    public function edit(User $utilizador)
    {
        $utilizador->load('hotels');
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

    public function destroy(User $utilizador)
    {
        abort_if($utilizador->id === auth()->id(), 403, 'Não pode eliminar a sua própria conta.');
        $utilizador->delete();

        return redirect()->route('admin.utilizadores.index')
            ->with('success', 'Utilizador eliminado.');
    }

    public function toggleActive(User $utilizador)
    {
        abort_if($utilizador->id === auth()->id(), 403, 'Não pode suspender a sua própria conta.');

        $utilizador->update(['is_active' => !$utilizador->is_active]);

        $estado = $utilizador->is_active ? 'activado' : 'suspenso';
        return back()->with('success', "Utilizador {$estado} com sucesso!");
    }
}