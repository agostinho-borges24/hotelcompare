@extends('layouts.app')

@section('title', 'Hotéis em Benguela — HotelCompare')

@section('content')

<div class="container py-5">
    <div class="row g-4">

        {{-- ── SIDEBAR DE FILTROS ── --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top:80px;z-index:100;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-funnel me-2"></i>Filtros
                    </h6>

                    <form action="{{ route('hotels.index') }}" method="GET" id="filterForm">

                        {{-- Pesquisa --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Pesquisar</label>
                            <input type="text" name="q" class="form-control form-control-sm"
                                   placeholder="Nome ou localização..."
                                   value="{{ request('q') }}">
                        </div>

                        {{-- Estrelas --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Estrelas</label>
                            @foreach([5,4,3,2,1] as $star)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="stars" value="{{ $star }}"
                                           id="star{{ $star }}"
                                           {{ request('stars') == $star ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="star{{ $star }}">
                                        <span style="color:#f39c12;">{{ str_repeat('★', $star) }}</span>
                                        {{ str_repeat('☆', 5 - $star) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Preço máximo --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Preço máx/noite: <strong id="priceLabel">
                                    {{ request('max_price', 200000) }} Kz
                                </strong>
                            </label>
                            <input type="range" class="form-range" name="max_price"
                                   min="5000" max="200000" step="5000"
                                   value="{{ request('max_price', 200000) }}"
                                   id="priceRange">
                        </div>

                        {{-- Comodidades --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Comodidades</label>
                            @foreach($amenities as $category => $items)
                                <div class="small text-muted fw-semibold mt-2 mb-1">{{ $category }}</div>
                                @foreach($items as $amenity)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="amenities[]" value="{{ $amenity->id }}"
                                               id="amenity{{ $amenity->id }}"
                                               {{ in_array($amenity->id, (array)request('amenities')) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="amenity{{ $amenity->id }}">
                                            {{ $amenity->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search me-1"></i>Aplicar filtros
                            </button>
                            <a href="{{ route('hotels.index') }}" class="btn btn-outline-secondary btn-sm">
                                Limpar filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── LISTAGEM ── --}}
        <div class="col-lg-9">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold mb-0">Hotéis em Benguela</h4>
                    <p class="text-muted small mb-0">
                        {{ $hotels->total() }} hotel{{ $hotels->total() !== 1 ? 'is' : '' }} encontrado{{ $hotels->total() !== 1 ? 's' : '' }}
                    </p>
                </div>

                {{-- Ordenação --}}
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted">Ordenar:</label>
                    <select class="form-select form-select-sm" style="width:auto;"
                            onchange="this.form.submit()" form="filterForm" name="sort">
                        <option value="rating"     {{ request('sort','rating') === 'rating'     ? 'selected' : '' }}>Melhor avaliação</option>
                        <option value="price_asc"  {{ request('sort') === 'price_asc'           ? 'selected' : '' }}>Menor preço</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc'          ? 'selected' : '' }}>Maior preço</option>
                        <option value="newest"     {{ request('sort') === 'newest'              ? 'selected' : '' }}>Mais recente</option>
                    </select>
                </div>
            </div>

            {{-- Grid de hotéis --}}
            @if($hotels->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-building-x display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Nenhum hotel encontrado</h5>
                    <p class="text-muted small">Tente ajustar os filtros de pesquisa.</p>
                    <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary btn-sm">
                        Ver todos os hotéis
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($hotels as $hotel)
                        <div class="col-md-6 col-xl-4">
                            @include('public.partials.hotel-card', ['hotel' => $hotel])
                        </div>
                    @endforeach
                </div>

                {{-- Paginação --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $hotels->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Actualiza label do slider de preço
    const range = document.getElementById('priceRange');
    const label = document.getElementById('priceLabel');
    range?.addEventListener('input', () => {
        label.textContent = parseInt(range.value).toLocaleString('pt-AO') + ' Kz';
    });
</script>
@endpush