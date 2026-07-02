@extends('layouts.dashboard')

@section('title', 'Editar Hotel')
@section('page-title', 'Editar Hotel')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-9">
        <form action="{{ route('admin.hoteis.update', $hotel) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações Básicas</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Gestor do hotel *</label>
                            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('user_id', $hotel->user_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }} ({{ $manager->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nome do hotel *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $hotel->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Estrelas *</label>
                            <select name="stars" class="form-select" required>
                                @foreach([1,2,3,4,5] as $s)
                                    <option value="{{ $s }}" {{ old('stars', $hotel->stars) == $s ? 'selected' : '' }}>{{ $s }}★</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Descrição</label>
                            <textarea name="description" class="form-control" rows="3" maxlength="3000">{{ old('description', $hotel->description) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Morada *</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $hotel->address) }}" required>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Bairro / Zona</label>
                            <input type="text" name="neighborhood" class="form-control" value="{{ old('neighborhood', $hotel->neighborhood) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Preço/noite (Kz) *</label>
                            <input type="number" name="price_per_night" class="form-control"
                                   value="{{ old('price_per_night', $hotel->price_per_night) }}" min="0" step="500" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Telefone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $hotel->phone) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $hotel->email) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Website</label>
                            <input type="url" name="website" class="form-control" value="{{ old('website', $hotel->website) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Estado *</label>
                            <select name="status" class="form-select" required>
                                <option value="active"    {{ old('status', $hotel->status) === 'active'    ? 'selected' : '' }}>Activo</option>
                                <option value="pending"   {{ old('status', $hotel->status) === 'pending'   ? 'selected' : '' }}>Pendente</option>
                                <option value="suspended" {{ old('status', $hotel->status) === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nova imagem de capa</label>
                            <input type="file" name="cover_image" class="form-control form-control-sm" accept="image/*">
                            @if($hotel->cover_image)
                                <img src="{{ $hotel->cover_image_url }}" class="rounded-2 mt-2"
                                     style="height:50px;object-fit:cover;" alt="">
                            @endif
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured"
                                       value="1" id="is_featured"
                                       {{ old('is_featured', $hotel->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="is_featured">Hotel em destaque</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Comodidades --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Comodidades</h6>
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

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.hoteis.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-floppy me-1"></i>Guardar alterações
                </button>
            </div>
        </form>
    </div>
</div>

@endsection