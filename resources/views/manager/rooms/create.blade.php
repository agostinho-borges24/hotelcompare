@extends('layouts.dashboard')

@section('title', 'Adicionar Quarto')
@section('page-title', 'Adicionar Quarto')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">
        <form action="{{ route('manager.quartos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações do Quarto</h6>
                    <div class="row g-3">

                        <div class="col-md-7">
                            <label class="form-label small fw-semibold">Nome do quarto *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Ex: Suite Deluxe, Standard Twin"
                                   required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Tipo *</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="single"       {{ old('type') === 'single'       ? 'selected' : '' }}>Individual</option>
                                <option value="double"       {{ old('type') === 'double'       ? 'selected' : '' }}>Duplo</option>
                                <option value="suite"        {{ old('type') === 'suite'        ? 'selected' : '' }}>Suite</option>
                                <option value="family"       {{ old('type') === 'family'       ? 'selected' : '' }}>Familiar</option>
                                <option value="presidential" {{ old('type') === 'presidential' ? 'selected' : '' }}>Presidencial</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Descrição</label>
                            <textarea name="description" class="form-control" rows="3"
                                      maxlength="1000">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Preço/noite (Kz) *</label>
                            <div class="input-group">
                                <input type="number" name="price_per_night"
                                       class="form-control @error('price_per_night') is-invalid @enderror"
                                       value="{{ old('price_per_night') }}"
                                       min="0" step="500" required>
                                <span class="input-group-text">Kz</span>
                            </div>
                            @error('price_per_night') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Máx. hóspedes *</label>
                            <input type="number" name="max_guests"
                                   class="form-control @error('max_guests') is-invalid @enderror"
                                   value="{{ old('max_guests', 2) }}" min="1" max="20" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nº de camas *</label>
                            <input type="number" name="beds"
                                   class="form-control @error('beds') is-invalid @enderror"
                                   value="{{ old('beds', 1) }}" min="1" max="10" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Total de unidades *</label>
                            <input type="number" name="total_units"
                                   class="form-control @error('total_units') is-invalid @enderror"
                                   value="{{ old('total_units', 1) }}" min="1" required>
                            <div class="form-text">Quantos quartos deste tipo existem</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Imagem do quarto</label>
                            <input type="file" name="cover_image"
                                   class="form-control form-control-sm @error('cover_image') is-invalid @enderror"
                                   accept="image/*">
                        </div>

                    </div>
                </div>
            </div>

            {{-- Comodidades do quarto --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Comodidades do quarto</h6>
                    <div class="row g-2">
                        @foreach([
                            ['has_ac',               'Ar Condicionado', 'wind'],
                            ['has_tv',               'Televisão', 'tv'],
                            ['has_wifi',             'Wi-Fi', 'wifi'],
                            ['has_private_bathroom', 'Casa de banho privativa', 'droplet'],
                        ] as [$field, $label, $icon])
                            <div class="col-md-3 col-6">
                                <div class="form-check border rounded-3 p-3">
                                    <input class="form-check-input" type="checkbox"
                                           name="{{ $field }}" value="1" id="{{ $field }}"
                                           {{ old($field) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="{{ $field }}">
                                        <i class="bi bi-{{ $icon }} me-1"></i>{{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('manager.quartos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-plus-circle me-1"></i>Criar quarto
                </button>
            </div>
        </form>
    </div>
</div>

@endsection