@extends('layouts.app')

@section('title', 'HotelCompare Benguela — Encontre e Compare Hotéis')

@section('content')

{{-- ── HERO ── --}}
<section class="py-5 text-white" style="background: linear-gradient(135deg, #0d1b2a 0%, #1a5276 100%);">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold mb-3">
                    Encontre o hotel perfeito<br>
                    <span style="color: #f39c12;">em Benguela</span>
                </h1>
                <p class="lead mb-4 text-white-75">
                    Compare preços, avaliações e serviços de hotéis em tempo real.
                    Tome a melhor decisão com informações sempre actualizadas.
                </p>

                {{-- Barra de pesquisa rápida --}}
                <form action="{{ route('hotels.index') }}" method="GET">
                    <div class="input-group input-group-lg shadow">
                        <span class="input-group-text bg-white border-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-0 ps-0"
                               placeholder="Pesquisar hotéis em Benguela...">
                        <button class="btn px-4 fw-semibold"
                                style="background:#f39c12;color:#fff;border:none;"
                                type="submit">Pesquisar</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-6">
                {{-- Estatísticas --}}
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="p-4 rounded-3" style="background:rgba(255,255,255,.08);">
                            <div class="display-6 fw-bold" style="color:#f39c12;">{{ $totalHotels }}</div>
                            <div class="small text-white-75">Hotéis registados</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 rounded-3" style="background:rgba(255,255,255,.08);">
                            <div class="display-6 fw-bold" style="color:#f39c12;">{{ $totalReviews }}</div>
                            <div class="small text-white-75">Avaliações publicadas</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 rounded-3" style="background:rgba(255,255,255,.08);">
                            <div class="display-6 fw-bold" style="color:#f39c12;">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                            <div class="small text-white-75">Actualizações em tempo real</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 rounded-3" style="background:rgba(255,255,255,.08);">
                            <div class="display-6 fw-bold" style="color:#f39c12;">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                            <div class="small text-white-75">Comparação lado a lado</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FILTROS RÁPIDOS ── --}}
<section class="py-3 bg-white border-bottom shadow-sm">
    <div class="container">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <span class="small text-muted fw-semibold me-2">Filtrar por:</span>
            @foreach(['1','2','3','4','5'] as $star)
                <a href="{{ route('hotels.index', ['stars' => $star]) }}"
                   class="btn btn-sm btn-outline-secondary">
                    {{ str_repeat('★', (int)$star) }} {{ $star }} estrela{{ $star > 1 ? 's' : '' }}
                </a>
            @endforeach
            <a href="{{ route('hotels.index', ['sort' => 'price_asc']) }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-sort-numeric-up me-1"></i>Menor preço
            </a>
            <a href="{{ route('hotels.index', ['sort' => 'rating']) }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-star me-1"></i>Melhor avaliação
            </a>
        </div>
    </div>
</section>

{{-- ── HOTÉIS EM DESTAQUE ── --}}
@if($featuredHotels->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Hotéis em Destaque</h2>
                <p class="text-muted small mb-0">Os melhores avaliados da nossa plataforma</p>
            </div>
            <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary btn-sm">
                Ver todos <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredHotels as $hotel)
                <div class="col-md-6 col-lg-4">
                    @include('public.partials.hotel-card', ['hotel' => $hotel])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── COMO FUNCIONA ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="fw-bold text-center mb-2">Como funciona</h2>
        <p class="text-center text-muted mb-5">Em três passos simples encontra o hotel ideal</p>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:72px;height:72px;background:#e8f4fd;">
                        <i class="bi bi-search fs-2" style="color:#1a5276;"></i>
                    </div>
                    <h5 class="fw-semibold">1. Pesquise</h5>
                    <p class="text-muted small">Pesquise hotéis por nome, localização, preço ou número de estrelas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:72px;height:72px;background:#fef9e7;">
                        <i class="bi bi-arrow-left-right fs-2" style="color:#f39c12;"></i>
                    </div>
                    <h5 class="fw-semibold">2. Compare</h5>
                    <p class="text-muted small">Compare até 3 hotéis lado a lado com informações em tempo real.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:72px;height:72px;background:#eafaf1;">
                        <i class="bi bi-check-circle fs-2" style="color:#27ae60;"></i>
                    </div>
                    <h5 class="fw-semibold">3. Decida</h5>
                    <p class="text-muted small">Escolha com confiança baseado em avaliações reais de outros hóspedes.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── MAIS RECENTES ── --}}
@if($latestHotels->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Recém Adicionados</h2>
                <p class="text-muted small mb-0">Os hotéis mais recentes na plataforma</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($latestHotels as $hotel)
                <div class="col-md-6 col-lg-3">
                    @include('public.partials.hotel-card', ['hotel' => $hotel, 'compact' => true])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CTA PARA HOTÉIS ── --}}
<section class="py-5 text-white" style="background: linear-gradient(135deg, #1a5276, #0d1b2a);">
    <div class="container text-center py-3">
        <h2 class="fw-bold mb-3">Tem um hotel em Benguela?</h2>
        <p class="lead mb-4 text-white-75">
            Registe o seu hotel gratuitamente e chegue a milhares de turistas.<br>
            Actualize disponibilidade e preços em tempo real.
        </p>
        <a href="{{ route('register') }}" class="btn btn-lg px-5 fw-semibold"
           style="background:#f39c12;color:#fff;border:none;">
            <i class="bi bi-plus-circle me-2"></i>Registar o meu hotel
        </a>
    </div>
</section>

@endsection