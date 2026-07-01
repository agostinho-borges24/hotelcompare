{{--
    Variáveis disponíveis:
    - $hotel  (required) — instância do Model Hotel
    - $compact (optional) — layout mais pequeno
--}}
<div class="card hotel-card h-100">
    {{-- Imagem --}}
    <a href="{{ route('hotels.show', $hotel->slug) }}">
        <img src="{{ $hotel->cover_image_url }}"
             alt="{{ $hotel->name }}"
             class="card-img-top">
    </a>

    {{-- Badge destaque --}}
    @if($hotel->is_featured)
        <span class="position-absolute top-0 start-0 m-2 badge"
              style="background:#f39c12;">
            <i class="bi bi-star-fill me-1"></i>Destaque
        </span>
    @endif

    <div class="card-body d-flex flex-column p-3">
        {{-- Estrelas --}}
        <div class="stars small mb-1">{{ $hotel->stars_label }}</div>

        {{-- Nome --}}
        <h6 class="fw-bold mb-1">
            <a href="{{ route('hotels.show', $hotel->slug) }}"
               class="text-decoration-none text-dark stretched-link">
                {{ $hotel->name }}
            </a>
        </h6>

        {{-- Localização --}}
        <p class="text-muted small mb-2">
            <i class="bi bi-geo-alt me-1"></i>
            {{ $hotel->neighborhood ?? $hotel->city }}
        </p>

        @unless($compact ?? false)
            {{-- Comodidades (máx 4) --}}
            @if($hotel->amenities->isNotEmpty())
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach($hotel->amenities->take(4) as $amenity)
                        <span class="badge bg-light text-secondary border" style="font-size:.7rem;">
                            {{ $amenity->name }}
                        </span>
                    @endforeach
                    @if($hotel->amenities->count() > 4)
                        <span class="badge bg-light text-secondary border" style="font-size:.7rem;">
                            +{{ $hotel->amenities->count() - 4 }}
                        </span>
                    @endif
                </div>
            @endif
        @endunless

        <div class="mt-auto d-flex justify-content-between align-items-end">
            {{-- Avaliação --}}
            <div>
                @if($hotel->total_reviews > 0)
                    <span class="fw-bold" style="color:#1a5276;">
                        ★ {{ number_format($hotel->avg_rating, 1) }}
                    </span>
                    <span class="text-muted small">({{ $hotel->total_reviews }})</span>
                @else
                    <span class="text-muted small">Sem avaliações</span>
                @endif
            </div>

            {{-- Preço --}}
            @if($hotel->price_per_night)
                <div class="text-end">
                    <div class="text-muted" style="font-size:.7rem;">A partir de</div>
                    <div class="fw-bold" style="color:#f39c12;">
                        {{ number_format($hotel->price_per_night, 0) }} Kz<span class="text-muted fw-normal" style="font-size:.75rem;">/noite</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Botão comparar --}}
    @unless($compact ?? false)
        <div class="card-footer bg-transparent border-top-0 pt-0 px-3 pb-3">
            <a href="{{ route('hotels.compare', ['ids' => $hotel->id]) }}"
               class="btn btn-sm btn-outline-primary w-100"
               style="position:relative;z-index:1;">
                <i class="bi bi-arrow-left-right me-1"></i>Comparar
            </a>
        </div>
    @endunless
</div>