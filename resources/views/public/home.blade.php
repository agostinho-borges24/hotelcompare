@extends('layouts.app')

@section('title', 'HotelCompare Benguela — Encontre e Compare Hotéis')

@section('content')

{{-- ── HERO ── --}}
<section style="background: linear-gradient(135deg, #0d1b2a 0%, #1a5276 100%); min-height: 520px; display:flex; align-items:center;">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-1 rounded-pill"
                     style="background:rgba(243,156,18,.15);border:1px solid rgba(243,156,18,.3);">
                    <span class="rounded-circle" style="width:8px;height:8px;background:#f39c12;display:inline-block;"></span>
                    <span class="small" style="color:#f39c12;">Actualizações em tempo real</span>
                </div>
                <h1 class="display-4 fw-bold text-white mb-3" style="line-height:1.2;">
                    Encontre o hotel perfeito
                    <span style="color:#f39c12;">em Benguela</span>
                </h1>
                <p class="fs-5 mb-4" style="color:rgba(255,255,255,.75);">
                    Compare preços, avaliações e serviços de hotéis com informações sempre actualizadas. Tome a melhor decisão com total confiança.
                </p>

                <form action="{{ route('hotels.index') }}" method="GET">
                    <div class="input-group input-group-lg shadow-lg" style="border-radius:14px;overflow:hidden;">
                        <span class="input-group-text bg-white border-0 ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-0 ps-1"
                               placeholder="Nome do hotel, bairro...">
                        <button class="btn px-4 fw-semibold border-0"
                                style="background:#f39c12;color:#fff;" type="submit">
                            Pesquisar
                        </button>
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach(['5 Estrelas', '4 Estrelas', 'Piscina', 'Wi-Fi'] as $tag)
                        <a href="{{ route('hotels.index', ['q' => $tag]) }}"
                           class="badge rounded-pill text-decoration-none px-3 py-2"
                           style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.2);font-size:.8rem;">
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    @php
                        $heroStats = [
                            ['icon' => 'building', 'value' => $totalHotels, 'label' => 'Hotéis registados', 'color' => '#3b82f6'],
                            ['icon' => 'star-fill', 'value' => $totalReviews, 'label' => 'Avaliações publicadas', 'color' => '#f39c12'],
                            ['icon' => 'lightning-charge-fill', 'value' => 'Live', 'label' => 'Disponibilidade em tempo real', 'color' => '#10b981'],
                            ['icon' => 'arrow-left-right', 'value' => '3x', 'label' => 'Comparação lado a lado', 'color' => '#8b5cf6'],
                        ];
                    @endphp
                    @foreach($heroStats as $stat)
                        <div class="col-6">
                            <div class="p-4 rounded-3 h-100"
                                 style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);backdrop-filter:blur(10px);">
                                <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2"
                                     style="width:40px;height:40px;background:{{ $stat['color'] }}22;">
                                    <i class="bi bi-{{ $stat['icon'] }}" style="color:{{ $stat['color'] }};font-size:1.1rem;"></i>
                                </div>
                                <div class="fs-3 fw-bold text-white">{{ $stat['value'] }}</div>
                                <div class="small" style="color:rgba(255,255,255,.6);">{{ $stat['label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FILTROS RÁPIDOS ── --}}
<section class="py-3 bg-white shadow-sm sticky-top" style="top:64px;z-index:100;">
    <div class="container">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <span class="small text-muted fw-semibold me-1">
                <i class="bi bi-funnel me-1"></i>Filtrar:
            </span>
            @foreach([
                ['label' => '5 Estrelas', 'params' => ['stars' => 5]],
                ['label' => '4 Estrelas', 'params' => ['stars' => 4]],
                ['label' => '3 Estrelas', 'params' => ['stars' => 3]],
                ['label' => 'Menor preço', 'params' => ['sort' => 'price_asc']],
                ['label' => 'Melhor avaliação', 'params' => ['sort' => 'rating']],
                ['label' => 'Mais recentes', 'params' => ['sort' => 'newest']],
            ] as $filter)
                <a href="{{ route('hotels.index', $filter['params']) }}"
                   class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                   style="font-size:.8rem;">
                    {{ $filter['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── HOTÉIS EM DESTAQUE ── --}}
@if($featuredHotels->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="small fw-semibold mb-1" style="color:#f39c12;">
                    <i class="bi bi-star-fill me-1"></i>DESTAQUES
                </div>
                <h2 class="fw-bold mb-0">Hotéis em Destaque</h2>
                <p class="text-muted small mb-0">Os mais bem avaliados da plataforma</p>
            </div>
            <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary btn-sm px-4 rounded-pill">
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
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="small fw-semibold mb-1" style="color:#1a5276;">
                <i class="bi bi-info-circle me-1"></i>SIMPLES E RÁPIDO
            </div>
            <h2 class="fw-bold mb-2">Como funciona</h2>
            <p class="text-muted">Em três passos simples encontra o hotel ideal em Benguela</p>
        </div>
        <div class="row g-4">
            @php
                $steps = [
                    ['icon' => 'search', 'color' => '#1a5276', 'bg' => '#e8f4fd', 'num' => '01',
                     'title' => 'Pesquise', 'desc' => 'Filtre por nome, localização, estrelas, preço ou comodidades.'],
                    ['icon' => 'arrow-left-right', 'color' => '#f39c12', 'bg' => '#fef9e7', 'num' => '02',
                     'title' => 'Compare', 'desc' => 'Compare até 3 hotéis lado a lado com dados em tempo real.'],
                    ['icon' => 'check-circle-fill', 'color' => '#10b981', 'bg' => '#eafaf1', 'num' => '03',
                     'title' => 'Decida', 'desc' => 'Escolha com confiança baseado em avaliações reais de hóspedes.'],
                ];
            @endphp
            @foreach($steps as $step)
                <div class="col-lg-4 text-center">
                    <div class="bg-white rounded-4 p-4 shadow-sm h-100">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                 style="width:80px;height:80px;background:{{ $step['bg'] }};">
                                <i class="bi bi-{{ $step['icon'] }} fs-2" style="color:{{ $step['color'] }};"></i>
                            </div>
                            <span class="position-absolute top-0 end-0 badge rounded-pill fw-bold"
                                  style="background:{{ $step['color'] }};font-size:.7rem;">
                                {{ $step['num'] }}
                            </span>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $step['title'] }}</h5>
                        <p class="text-muted small mb-0">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── MAIS RECENTES ── --}}
@if($latestHotels->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="small fw-semibold mb-1" style="color:#10b981;">
                    <i class="bi bi-clock me-1"></i>RECÉM ADICIONADOS
                </div>
                <h2 class="fw-bold mb-0">Novos Hotéis</h2>
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

{{-- ── VANTAGENS ── --}}
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="small fw-semibold mb-1" style="color:#1a5276;">
                <i class="bi bi-trophy me-1"></i>VANTAGENS
            </div>
            <h2 class="fw-bold mb-2">Porquê usar o HotelCompare?</h2>
        </div>
        <div class="row g-4">
            @php
                $features = [
                    ['icon' => 'lightning-charge-fill', 'color' => '#f39c12', 'bg' => '#fef9e7',
                     'title' => 'Tempo Real',
                     'desc'  => 'Disponibilidade e preços actualizados instantaneamente pelos gestores.'],
                    ['icon' => 'shield-check', 'color' => '#10b981', 'bg' => '#eafaf1',
                     'title' => 'Avaliações Verificadas',
                     'desc'  => 'Todas as avaliações são moderadas para garantir autenticidade.'],
                    ['icon' => 'arrow-left-right', 'color' => '#3b82f6', 'bg' => '#eff6ff',
                     'title' => 'Comparação Fácil',
                     'desc'  => 'Compare até 3 hotéis simultaneamente com todos os detalhes.'],
                    ['icon' => 'geo-alt-fill', 'color' => '#8b5cf6', 'bg' => '#f5f3ff',
                     'title' => 'Foco em Benguela',
                     'desc'  => 'Plataforma dedicada à província de Benguela com informações locais.'],
                    ['icon' => 'telephone', 'color' => '#ec4899', 'bg' => '#fdf2f8',
                     'title' => 'Contacto Directo',
                     'desc'  => 'Aceda directamente aos contactos sem intermediários ou comissões.'],
                    ['icon' => 'star-fill', 'color' => '#f59e0b', 'bg' => '#fffbeb',
                     'title' => 'Gratuito para Turistas',
                     'desc'  => 'Pesquise, compare e avalie hotéis completamente de forma gratuita.'],
                ];
            @endphp
            @foreach($features as $feature)
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex gap-3 p-3 bg-white rounded-3 shadow-sm h-100">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px;height:48px;background:{{ $feature['bg'] }};">
                            <i class="bi bi-{{ $feature['icon'] }}" style="color:{{ $feature['color'] }};font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold mb-1">{{ $feature['title'] }}</div>
                            <p class="text-muted small mb-0">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="py-5 text-white" style="background:linear-gradient(135deg,#0d1b2a,#1a5276);">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="small fw-semibold mb-2" style="color:#f39c12;">
                    <i class="bi bi-building me-1"></i>PARA GESTORES DE HOTÉIS
                </div>
                <h2 class="fw-bold text-white mb-2">Tem um hotel em Benguela?</h2>
                <p class="mb-0" style="color:rgba(255,255,255,.75);">
                    Registe o seu hotel gratuitamente e chegue a milhares de turistas.
                    Actualize disponibilidade e preços em tempo real directamente do seu painel.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('register') }}"
                   class="btn btn-lg px-5 fw-semibold rounded-pill"
                   style="background:#f39c12;color:#fff;border:none;">
                    <i class="bi bi-plus-circle me-2"></i>Registar o meu hotel
                </a>
                <div class="small mt-2" style="color:rgba(255,255,255,.5);">
                    Gratuito · Sem comissões · Controlo total
                </div>
            </div>
        </div>
    </div>
</section>

@endsection