@extends('layouts.dashboard')

@section('title', 'Criar Utilizador')
@section('page-title', 'Criar Utilizador')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-6">
        <form action="{{ route('admin.utilizadores.store') }}" method="POST">
            @csrf

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações do Utilizador</h6>
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nome *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Email *</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Palavra-passe *</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required minlength="8">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Confirmar palavra-passe *</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" required minlength="8">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Perfil *</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="guest"         {{ old('role') === 'guest'         ? 'selected' : '' }}>Hóspede</option>
                                <option value="hotel_manager" {{ old('role') === 'hotel_manager' ? 'selected' : '' }}>Gestor de Hotel</option>
                                <option value="admin"         {{ old('role') === 'admin'         ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Telefone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone') }}"
                                   placeholder="+244 9xx xxx xxx">
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-between">
                <a href="{{ route('admin.utilizadores.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Voltar
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-person-plus me-1"></i>Criar utilizador
                </button>
            </div>
        </form>
    </div>
</div>

@endsection