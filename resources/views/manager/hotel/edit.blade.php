@extends('layouts.dashboard')

@section('title', 'Editar Hotel')
@section('page-title', 'Editar Informações do Hotel')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-9">
        <form action="{{ route('manager.hotel.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- ── INFORMAÇÕES BÁSICAS ── --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações Básicas</h6>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Nome do hotel *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $hotel->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Estrelas *</label>
                            <select name="stars" class="form-select @error('stars') is-invalid @enderror" required>
                                @foreach([1,2,3,4,5] as $s)
                                    <option value="{{ $s }}" {{ old('stars', $hotel->stars) == $s ? 'selected' : '' }}>
                                        {{ str_repeat('★', $s) }} {{ $s }} estrela{{ $s > 1 ? 's' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stars') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Descrição</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="4" maxlength="3000"
                                      placeholder="Descreva o seu hotel, história, diferenciais...">{{ old('description', $hotel->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── LOCALIZAÇÃO E CONTACTO ── --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Localização e Contacto</h6>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Morada *</label>
                            <input type="text" name="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $hotel->address) }}" required>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Bairro / Zona</label>
                            <input type="text" name="neighborhood"
                                   class="form-control @error('neighborhood') is-invalid @enderror"
                                   value="{{ old('neighborhood', $hotel->neighborhood) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Telefone</label>
                            <input type="text" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $hotel->phone) }}"
                                   placeholder="+244 9xx xxx xxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $hotel->email) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Website</label>
                            <input type="url" name="website"
                                   class="form-control @error('website') is-invalid @enderror"
                                   value="{{ old('website', $hotel->website) }}"
                                   placeholder="https://...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PREÇO ── --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Preço</h6>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Preço mínimo por noite (Kz) *</label>
                            <div class="input-group">
                                <input type="number" name="price_per_night"
                                       class="form-control @error('price_per_night') is-invalid @enderror"
                                       value="{{ old('price_per_night', $hotel->price_per_night) }}"
                                       min="0" step="500" required>
                                <span class="input-group-text">Kz</span>
                                @error('price_per_night') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text">Preço base exibido na listagem</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── COMODIDADES ── --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Comodidades</h6>
                    @foreach($amenities as $category => $items)
                        <div class="mb-3">
                            <div class="small fw-semibold text-muted mb-2">{{ $category }}</div>
                            <div class="row g-2">
                                @foreach($items as $amenity)
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="amenities[]" value="{{ $amenity->id }}"
                                                   id="am{{ $amenity->id }}"
                                                   {{ $hotel->amenities->contains($amenity->id) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="am{{ $amenity->id }}">
                                                {{ $amenity->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── ACÇÕES ── --}}
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('manager.hotel.index') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-floppy me-1"></i>Guardar alterações
                </button>
            </div>

        </form>
    </div>
</div>

@endsection