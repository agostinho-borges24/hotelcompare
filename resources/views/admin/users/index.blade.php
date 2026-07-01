@extends('layouts.dashboard')

@section('title', 'Gestão de Utilizadores')
@section('page-title', 'Utilizadores')

@section('content')

{{-- Filtros --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.utilizadores.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Pesquisar por nome ou email..." value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select form-select-sm">
                        <option value="">Todos os perfis</option>
                        <option value="admin"         {{ request('role') === 'admin'         ? 'selected' : '' }}>Administrador</option>
                        <option value="hotel_manager" {{ request('role') === 'hotel_manager' ? 'selected' : '' }}>Gestor de Hotel</option>
                        <option value="guest"         {{ request('role') === 'guest'         ? 'selected' : '' }}>Hóspede</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Utilizador</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Registado em</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                            justify-content-center flex-shrink-0"
                                     style="width:36px;height:36px;font-size:.85rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="fw-semibold small">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="small text-muted">{{ $user->email }}</td>
                        <td>
                            <span class="badge rounded-pill
                                {{ $user->role === 'admin' ? 'bg-primary' :
                                   ($user->role === 'hotel_manager' ? 'bg-info text-dark' : 'bg-secondary') }}">
                                {{ match($user->role) {
                                    'admin'         => 'Admin',
                                    'hotel_manager' => 'Gestor',
                                    default         => 'Hóspede'
                                } }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->is_active ? 'Activo' : 'Suspenso' }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.utilizadores.edit', $user) }}"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                title="{{ $user->is_active ? 'Suspender' : 'Activar' }}">
                                            <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Nenhum utilizador encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection