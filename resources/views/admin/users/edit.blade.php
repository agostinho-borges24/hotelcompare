@extends('layouts.dashboard')

@section('title', 'Editar Utilizador')
@section('page-title', 'Editar Utilizador')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-6">
        <form action="{{ route('admin.utilizadores.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações do Utilizador</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nome *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Email *</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Perfil *</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="guest"         {{ old('role', $user->role) === 'guest'         ? 'selected' : '' }}>Hóspede</option>
                                <option value="hotel_manager" {{ old('role', $user->role) === 'hotel_manager' ? 'selected' : '' }}>Gestor de Hotel</option>
                                <option value="admin"         {{ old('role', $user->role) === 'admin'         ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Telefone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+244 9xx xxx xxx">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info do hotel associado --}}
            @if($user->isHotelManager() && $user->hotels->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-primary border-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-2">Hotel Associado</h6>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $user->hotels->first()->cover_image_url }}"
                                 class="rounded-2" style="width:50px;height:50px;object-fit:cover;" alt="">
                            <div>
                                <div class="fw-semibold small">{{ $user->hotels->first()->name }}</div>
                                <div class="text-muted small">{{ $user->hotels->first()->address }}</div>
                                <span class="badge {{ $user->hotels->first()->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $user->hotels->first()->status === 'active' ? 'Activo' : 'Pendente' }}
                                </span>
                            </div>
                            <a href="{{ route('admin.hoteis.edit', $user->hotels->first()) }}"
                               class="btn btn-sm btn-outline-primary ms-auto">
                                <i class="bi bi-pencil me-1"></i>Editar hotel
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="d-flex gap-2 justify-content-between">
                <a href="{{ route('admin.utilizadores.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-floppy me-1"></i>Guardar alterações
                </button>
            </div>
        </form>
    </div>
</div>

@endsection