@extends('layouts.dashboard')

@section('title', 'Criar Hotel')
@section('page-title', 'Criar Hotel')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-9">
        <form action="{{ route('admin.hoteis.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Informações Básicas</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Gestor do hotel *</label>
                            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Seleccionar gestor...</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('user_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }} ({{ $manager->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nome do hotel *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Estrelas *</label>
                            <select name="stars" class="form-select" required>
                                @foreach([1,2,3,4,5] as $s)
                                    <option value="{{ $s }}" {{ old('stars', 3) == $s ? 'selected' : '' }}>
                                        {{ $s }}★
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Descrição</label>
                            <textarea name="description" class="form-control" rows="3" maxlength="3000">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Morada *</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" required>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Bairro / Zona</label>
                            <input type="text" name="neighborhood" class="form-control" value="{{ old('neighborhood') }}">
                        </div>
                        @include('partials.map-picker')
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Preço/noite (Kz) *</label>
                            <input type="number" name="price_per_night"
                                   class="form-control @error('price_per_night') is-invalid @enderror"
                                   value="{{ old('price_per_night') }}" min="0" step="500" required>
                            @error('price_per_night') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Telefone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Website</label>
                            <input type="url" name="website" class="form-control" value="{{ old('website') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Estado *</label>
                            <select name="status" class="form-select" required>
                                <option value="pending"   {{ old('status') === 'pending'   ? 'selected' : '' }}>Pendente</option>
                                <option value="active"    {{ old('status') === 'active'    ? 'selected' : '' }}>Activo</option>
                                <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Imagem de capa</label>
                            <input type="file" name="cover_image" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured"
                                       value="1" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="is_featured">
                                    Hotel em destaque
                                </label>
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
                                                   {{ in_array($amenity->id, (array)old('amenities')) ? 'checked' : '' }}>
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
                    <i class="bi bi-plus-circle me-1"></i>Criar hotel
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const lat = parseFloat(document.getElementById('latInput').value) || -12.5700;
    const lng = parseFloat(document.getElementById('lngInput').value) || 13.4000;

    const map = L.map('hotelMapPicker').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    // Clique no mapa move o marcador
    map.on('click', (e) => {
        marker.setLatLng(e.latlng);
        document.getElementById('latInput').value = e.latlng.lat.toFixed(7);
        document.getElementById('lngInput').value = e.latlng.lng.toFixed(7);
    });

    // Arrastar o marcador actualiza os inputs
    marker.on('dragend', (e) => {
        const pos = e.target.getLatLng();
        document.getElementById('latInput').value = pos.lat.toFixed(7);
        document.getElementById('lngInput').value = pos.lng.toFixed(7);
    });
</script>
@endpush

@endsection