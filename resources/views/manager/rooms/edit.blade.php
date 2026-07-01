@extends('layouts.dashboard')

@section('title', 'Editar Quarto')
@section('page-title', 'Editar Quarto')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">
        <form action="{{ route('manager.quartos.update', $room) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações do Quarto</h6>
                    <div class="row g-3">

                        <div class="col-md-7">
                            <label class="form-label small fw-semibold">Nome do quarto *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $room->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Tipo *</label>
                            <select name="type" class="form-select" required>
                                @foreach(['single' => 'Individual', 'double' => 'Duplo', 'suite' => 'Suite', 'family' => 'Familiar', 'presidential' => 'Presidencial'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('type', $room->type) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Descrição</label>
                            <textarea name="description" class="form-control" rows="3"
                                      maxlength="1000">{{ old('description', $room->description) }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Preço/noite (Kz) *</label>
                            <div class="input-group">
                                <input type="number" name="price_per_night"
                                       class="form-control @error('price_per_night') is-invalid @enderror"
                                       value="{{ old('price_per_night', $room->price_per_night) }}"
                                       min="0" step="500" required>
                                <span class="input-group-text">Kz</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Máx. hóspedes *</label>
                            <input type="number" name="max_guests" class="form-control"
                                   value="{{ old('max_guests', $room->max_guests) }}" min="1" max="20" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nº de camas *</label>
                            <input type="number" name="beds" class="form-control"
                                   value="{{ old('beds', $room->beds) }}" min="1" max="10" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Total de unidades *</label>
                            <input type="number" name="total_units" class="form-control"
                                   value="{{ old('total_units', $room->total_units) }}" min="1" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nova imagem</label>
                            <input type="file" name="cover_image" class="form-control form-control-sm" accept="image/*">
                            @if($room->cover_image)
                                <div class="mt-2">
                                    <img src="{{ $room->cover_image_url }}" class="rounded-2"
                                         style="height:60px;object-fit:cover;" alt="">
                                    <small class="text-muted d-block">Imagem actual</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Comodidades --}}
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
                                           {{ old($field, $room->$field) ? 'checked' : '' }}>
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
                    <i class="bi bi-floppy me-1"></i>Guardar alterações
                </button>
            </div>
        </form>
    </div>
</div>

@endsection