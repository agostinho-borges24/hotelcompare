@extends('layouts.app')

@section('title', 'Comparar Hotéis — HotelCompare')

@section('content')

<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Comparar Hotéis</h2>
        <p class="text-muted small">Seleccione até 3 hotéis para comparar lado a lado</p>
    </div>

    {{-- ── SELECTOR DE HOTÉIS ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('hotels.compare') }}" method="GET" id="compareForm">
                <div class="row g-3 align-items-end">
                    @for($i = 0; $i < 3; $i++)
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Hotel {{ $i + 1 }}</label>
                            <select name="ids[]" class="form-select form-select-sm">
                                <option value="">— Seleccionar hotel —</option>
                                @foreach($availableHotels as $h)
                                    <option value="{{ $h->id }}"
                                        {{ isset($hotels[$i]) && $hotels[$i]->id == $h->id ? 'selected' : '' }}>
                                        {{ $h->name }}
                                        ({{ str_repeat('★', $h->stars) }})
                                        @if($h->price_per_night)
                                            — {{ number_format($h->price_per_night, 0) }} Kz/noite
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endfor
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-arrow-left-right me-1"></i>Comparar
                        </button>
                        <a href="{{ route('hotels.compare') }}" class="btn btn-outline-secondary btn-sm ms-2">
                            Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABELA DE COMPARAÇÃO ── --}}
    @if($hotels->isNotEmpty())
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 align-middle">

                    {{-- Cabeçalho com imagens e nomes --}}
                    <thead>
                        <tr class="bg-light">
                            <th style="width:200px;" class="border-end bg-white"></th>
                            @foreach($hotels as $hotel)
                                <th class="text-center p-0">
                                    <div>
                                        <img src="{{ $hotel->cover_image_url }}"
                                             style="width:100%;height:160px;object-fit:cover;"
                                             alt="{{ $hotel->name }}">
                                        <div class="p-3">
                                            <div class="stars small mb-1">{{ $hotel->stars_label }}</div>
                                            <a href="{{ route('hotels.show', $hotel->slug) }}"
                                               class="fw-bold text-decoration-none text-dark">
                                                {{ $hotel->name }}
                                            </a>
                                            <div class="small text-muted mt-1">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $hotel->neighborhood ?? $hotel->city }}
                                            </div>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Preço --}}
                        <tr>
                            <td class="fw-semibold small bg-light">
                                <i class="bi bi-tag me-2"></i>Preço/noite
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="text-center">
                                    @if($hotel->price_per_night)
                                        <span class="fw-bold" style="color:#f39c12;">
                                            {{ number_format($hotel->price_per_night, 0) }} Kz
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Avaliação --}}
                        <tr class="table-light">
                            <td class="fw-semibold small bg-light">
                                <i class="bi bi-star me-2"></i>Avaliação média
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="text-center">
                                    @if($hotel->avg_rating > 0)
                                        <span class="fw-bold text-primary">
                                            ★ {{ number_format($hotel->avg_rating, 1) }}
                                        </span>
                                        <div class="small text-muted">({{ $hotel->total_reviews }} avaliações)</div>
                                    @else
                                        <span class="text-muted small">Sem avaliações</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Quartos disponíveis --}}
                        <tr>
                            <td class="fw-semibold small bg-light">
                                <i class="bi bi-door-open me-2"></i>Quartos
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        {{ $hotel->rooms->where('is_available', true)->count() }} disponíveis
                                    </span>
                                    <div class="small text-muted">de {{ $hotel->rooms->count() }} tipos</div>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Preço mínimo de quarto --}}
                        <tr class="table-light">
                            <td class="fw-semibold small bg-light">
                                <i class="bi bi-cash me-2"></i>Menor preço de quarto
                            </td>
                            @foreach($hotels as $hotel)
                                @php $minRoom = $hotel->rooms->where('is_available', true)->sortBy('price_per_night')->first(); @endphp
                                <td class="text-center">
                                    @if($minRoom)
                                        <span style="color:#f39c12;font-weight:600;">
                                            {{ number_format($minRoom->price_per_night, 0) }} Kz
                                        </span>
                                        <div class="small text-muted">{{ $minRoom->name }}</div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Comodidades --}}
                        <tr>
                            <td class="fw-semibold small bg-light align-top pt-3">
                                <i class="bi bi-check2-circle me-2"></i>Comodidades
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="text-center">
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        @foreach($allAmenities as $amenity)
                                            @php $has = $hotel->amenities->contains($amenity->id); @endphp
                                            <span class="badge {{ $has ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }}"
                                                  style="font-size:.7rem;">
                                                {{ $has ? '✓' : '✗' }} {{ $amenity->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Contacto --}}
                        <tr class="table-light">
                            <td class="fw-semibold small bg-light">
                                <i class="bi bi-telephone me-2"></i>Contacto
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="text-center small">
                                    @if($hotel->phone)
                                        <div><i class="bi bi-telephone me-1"></i>{{ $hotel->phone }}</div>
                                    @endif
                                    @if($hotel->email)
                                        <div><i class="bi bi-envelope me-1"></i>{{ $hotel->email }}</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Acções --}}
                        <tr>
                            <td class="bg-light"></td>
                            @foreach($hotels as $hotel)
                                <td class="text-center p-3">
                                    <a href="{{ route('hotels.show', $hotel->slug) }}"
                                       class="btn btn-primary btn-sm w-100">
                                        Ver detalhes
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-arrow-left-right display-1 text-muted"></i>
            <h5 class="mt-3 text-muted">Seleccione hotéis para comparar</h5>
            <p class="text-muted small">Use o selector acima para escolher até 3 hotéis.</p>
            <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary btn-sm">
                Ver todos os hotéis
            </a>
        </div>
    @endif

</div>

@endsection