@extends('layouts.dashboard')

@section('title', 'O Meu Perfil')
@section('page-title', 'O Meu Perfil')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7">

        {{-- ── DADOS PESSOAIS ── --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">Dados Pessoais</h6>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nome *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-7">
                            <label class="form-label small fw-semibold">Email *</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Telefone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+244 9xx xxx xxx">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-floppy me-1"></i>Guardar alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── PALAVRA-PASSE ── --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">Alterar Palavra-passe</h6>

                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Palavra-passe actual *</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nova palavra-passe *</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Confirmar nova palavra-passe *</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" required minlength="8">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-shield-lock me-1"></i>Alterar palavra-passe
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection