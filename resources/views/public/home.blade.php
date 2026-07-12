@extends('layouts.app')

@section('title', 'HotelCompare Benguela — Encontre e Compare Hotéis')

@section('content')

{{-- ── HERO BOOKING STYLE ── --}}
<section style="background: linear-gradient(135deg, #0d1b2a 0%, #1a5276 100%); padding: 3rem 0 5rem;">
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-white mb-2">
                Encontre o seu próximo hotel em Benguela
            </h1>
            <p class="fs-5 text-white mb-0">Mais de {{ $totalHotels }} hotéis · {{ $totalReviews }} avaliações reais · Actualizações em tempo real</p>
        </div>

        {{-- Caixa de pesquisa estilo Booking --}}
        <form action="{{ route('hotels.index') }}" method="GET">
            <div class="bg-warning p-2 rounded-3 shadow-lg" style="max-width:900px;margin:0 auto;">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 rounded-start-3">
                                <i class="bi bi-search text-secondary fs-5"></i>
                            </span>
                            <input type="text" name="q"
                                   class="form-control border-0 py-3 rounded-end-3"
                                   placeholder="Nome do hotel ou bairro..."
                                   style="font-size:1rem;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="stars" class="form-select border-0 py-3 rounded-3" style="font-size:1rem;">
                            <option value="">Todas as categorias</option>
                            @foreach([5,4,3,2,1] as $s)
                                <option value="{{ $s }}">{{ str_repeat('★', $s) }} {{ $s }} estrela{{ $s > 1 ? 's' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-select border-0 py-3 rounded-3" style="font-size:1rem;">
                            <option value="rating">Melhor avaliação</option>
                            <option value="price_asc">Menor preço</option>
                            <option value="price_desc">Maior preço</option>
                            <option value="newest">Mais recente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn w-100 py-3 fw-bold rounded-3"
                                style="background:#0d1b2a;color:#fff;font-size:1rem;">
                            <i class="bi bi-search me-1"></i>Pesquisar
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Badges populares --}}
        <div class="text-center mt-3 d-flex flex-wrap justify-content-center gap-2">
            <span class="text-white-50 small me-1">Popular:</span>
            @foreach(['Piscina', 'Wi-Fi Gratuito', 'Pequeno-Almoço', 'Estacionamento', 'Spa', 'Academia'] as $tag)
                <a href="{{ route('hotels.index', ['q' => $tag]) }}"
                   class="badge rounded-pill text-decoration-none px-3 py-2"
                   style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);font-size:.8rem;">
                    {{ $tag }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── STATS BAR ── --}}
<section class="py-0" style="background:#fff;border-bottom:1px solid #e5e7eb;">
    <div class="container">
        <div class="row g-0 text-center">
            @php
                $statsBar = [
                    ['icon' => 'building', 'value' => $totalHotels . '+', 'label' => 'Hotéis', 'color' => '#1a5276'],
                    ['icon' => 'star-fill', 'value' => $totalReviews . '+', 'label' => 'Avaliações', 'color' => '#f59e0b'],
                    ['icon' => 'lightning-charge-fill', 'value' => '100%', 'label' => 'Tempo Real', 'color' => '#10b981'],
                    ['icon' => 'people-fill', 'value' => '24/7', 'label' => 'Disponível', 'color' => '#8b5cf6'],
                    ['icon' => 'shield-check', 'value' => 'Grátis', 'label' => 'Para turistas', 'color' => '#ec4899'],
                ];
            @endphp
            @foreach($statsBar as $s)
                <div class="col py-3 border-end">
                    <i class="bi bi-{{ $s['icon'] }} fs-4 d-block mb-1" style="color:{{ $s['color'] }};"></i>
                    <div class="fw-bold" style="color:#1a1a1a;">{{ $s['value'] }}</div>
                    <div class="text-muted" style="font-size:.75rem;">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── BAIRROS / ZONAS DE BENGUELA ── --}}
<section class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <h2 class="fw-bold mb-1">Explore Benguela por zona</h2>
        <p class="text-muted mb-4">Descubra os melhores hotéis em cada parte da cidade</p>

        <div class="row g-3">
            @php
                $zonas = [
                    ['nome' => 'Centro da Cidade', 'img' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80', 'desc' => 'Hotéis no coração de Benguela'],
                    ['nome' => 'Restinga', 'img' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=400&q=80', 'desc' => 'Junto à praia e ao mar'],
                    ['nome' => 'Canata', 'img' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=400&q=80', 'desc' => 'Zona residencial tranquila'],
                    ['nome' => 'Bairro Operário', 'img' => 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=400&q=80', 'desc' => 'Acessível e central'],
                ];
            @endphp

            <div class="col-md-6">
                <a href="{{ route('hotels.index', ['q' => $zonas[0]['nome']]) }}"
                   class="text-decoration-none d-block position-relative rounded-3 overflow-hidden"
                   style="height:280px;">
                    <img src="{{ $zonas[0]['img'] }}" class="w-100 h-100 object-fit-cover" alt="{{ $zonas[0]['nome'] }}">
                    <div class="position-absolute bottom-0 start-0 end-0 p-3"
                         style="background:linear-gradient(transparent,rgba(0,0,0,.7));">
                        <h5 class="text-white fw-bold mb-0">{{ $zonas[0]['nome'] }}</h5>
                        <small class="text-white">{{ $zonas[0]['desc'] }}</small>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <div class="row g-3 h-100">
                    @foreach(array_slice($zonas, 1) as $zona)
                        <div class="col-6">
                            <a href="{{ route('hotels.index', ['q' => $zona['nome']]) }}"
                               class="text-decoration-none d-block position-relative rounded-3 overflow-hidden"
                               style="height:130px;">
                                <img src="{{ $zona['img'] }}" class="w-100 h-100 object-fit-cover" alt="{{ $zona['nome'] }}">
                                <div class="position-absolute bottom-0 start-0 end-0 p-2"
                                     style="background:linear-gradient(transparent,rgba(0,0,0,.7));">
                                    <div class="text-white fw-semibold small">{{ $zona['nome'] }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── HOTÉIS EM DESTAQUE ── --}}
@if($featuredHotels->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Hotéis em Destaque</h2>
                <p class="text-muted small mb-0">Seleccionados pela nossa equipa com base nas melhores avaliações</p>
            </div>
            <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary rounded-pill px-4">
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

{{-- ── OFERTA ESPECIAL BANNER ── --}}
<section class="py-4" style="background:linear-gradient(135deg,#0d1b2a,#1a5276);">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-2 text-center">
                <i class="bi bi-percent text-warning" style="font-size:4rem;"></i>
            </div>
            <div class="col-md-7">
                <h4 class="text-white fw-bold mb-1">Registe o seu hotel e chegue a mais clientes</h4>
                <p class="text-white mb-0 small">
                    Junte-se à plataforma de comparação de hotéis de Benguela.
                    Gestão simples, actualizações em tempo real e visibilidade garantida.
                </p>
            </div>
            <div class="col-md-3 text-md-end">
                <a href="{{ route('register') }}"
                   class="btn btn-warning fw-bold px-4 py-2 rounded-pill">
                    <i class="bi bi-building-add me-2"></i>Registar hotel
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ── NOVOS HOTÉIS ── --}}
@if($latestHotels->isNotEmpty())
<section class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Recém Adicionados</h2>
                <p class="text-muted small mb-0">Os hotéis mais recentes na nossa plataforma</p>
            </div>
        </div>
        <div class="row g-3">
            @foreach($latestHotels as $hotel)
                <div class="col-6 col-md-3">
                    @include('public.partials.hotel-card', ['hotel' => $hotel, 'compact' => true])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── COMPARAÇÃO DESTAQUE ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="small fw-semibold text-primary mb-2">
                    <i class="bi bi-arrow-left-right me-1"></i>FERRAMENTA EXCLUSIVA
                </div>
                <h2 class="fw-bold mb-3">Compare hotéis lado a lado</h2>
                <p class="text-muted mb-4">
                    A nossa ferramenta de comparação permite-lhe analisar até 3 hotéis simultaneamente.
                    Veja preços, comodidades, avaliações e disponibilidade em tempo real — tudo numa só página.
                </p>
                <div class="d-flex flex-column gap-3 mb-4">
                    @foreach([
                        ['icon' => 'check-circle-fill', 'color' => '#10b981', 'text' => 'Preços e disponibilidade actualizados em tempo real'],
                        ['icon' => 'check-circle-fill', 'color' => '#10b981', 'text' => 'Tabela completa de comodidades lado a lado'],
                        ['icon' => 'check-circle-fill', 'color' => '#10b981', 'text' => 'Avaliações e classificações dos hóspedes'],
                        ['icon' => 'check-circle-fill', 'color' => '#10b981', 'text' => 'Contacto directo com o hotel sem intermediários'],
                    ] as $item)
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-{{ $item['icon'] }} fs-5 flex-shrink-0" style="color:{{ $item['color'] }};"></i>
                            <span class="text-muted">{{ $item['text'] }}</span>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('hotels.compare') }}"
                   class="btn btn-primary btn-lg rounded-pill px-5">
                    <i class="bi bi-arrow-left-right me-2"></i>Comparar hotéis agora
                </a>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <div class="bg-light rounded-4 p-4 shadow-sm">
                        {{-- Simulação visual da tabela de comparação --}}
                        <div class="d-flex gap-2 mb-3">
                            <div class="flex-fill text-center">
                                <div class="rounded-3 mb-2 overflow-hidden" style="height:80px;background:#e2e8f0;"></div>
                                <div class="fw-semibold small">Hotel A</div>
                                <div class="text-warning small">★★★★★</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-arrow-left-right text-primary fs-4"></i>
                            </div>
                            <div class="flex-fill text-center">
                                <div class="rounded-3 mb-2 overflow-hidden" style="height:80px;background:#e2e8f0;"></div>
                                <div class="fw-semibold small">Hotel B</div>
                                <div class="text-warning small">★★★★</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-arrow-left-right text-primary fs-4"></i>
                            </div>
                            <div class="flex-fill text-center">
                                <div class="rounded-3 mb-2 overflow-hidden" style="height:80px;background:#e2e8f0;"></div>
                                <div class="fw-semibold small">Hotel C</div>
                                <div class="text-warning small">★★★</div>
                            </div>
                        </div>
                        @foreach(['Preço/noite', 'Piscina', 'Wi-Fi', 'Pequeno-almoço', 'Avaliação'] as $row)
                            <div class="d-flex border-top py-2 align-items-center">
                                <div class="text-muted small" style="width:120px;">{{ $row }}</div>
                                <div class="flex-fill text-center">
                                    <i class="bi bi-check-circle-fill text-success small"></i>
                                </div>
                                <div class="flex-fill text-center">
                                    <i class="bi bi-{{ in_array($row, ['Wi-Fi', 'Avaliação', 'Preço/noite']) ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} small"></i>
                                </div>
                                <div class="flex-fill text-center">
                                    <i class="bi bi-{{ in_array($row, ['Preço/noite', 'Wi-Fi']) ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} small"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Badge flutuante --}}
                    <div class="position-absolute top-0 end-0 translate-middle">
                        <span class="badge rounded-pill px-3 py-2 shadow"
                              style="background:#10b981;font-size:.8rem;">
                            <i class="bi bi-lightning-charge-fill me-1"></i>Tempo Real
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── COMO FUNCIONA ── --}}
<section class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2">Como funciona</h2>
            <p class="text-muted">Simples, rápido e gratuito para turistas</p>
        </div>
        <div class="row g-4 text-center">
            @php
                $steps = [
                    ['num' => '1', 'icon' => 'search', 'color' => '#1a5276', 'bg' => '#eff6ff',
                     'title' => 'Pesquise', 'desc' => 'Use os filtros para encontrar hotéis por nome, zona, estrelas ou preço.'],
                    ['num' => '2', 'icon' => 'arrow-left-right', 'color' => '#f59e0b', 'bg' => '#fffbeb',
                     'title' => 'Compare', 'desc' => 'Seleccione até 3 hotéis e compare todos os detalhes lado a lado.'],
                    ['num' => '3', 'icon' => 'star-fill', 'color' => '#10b981', 'bg' => '#f0fdf4',
                     'title' => 'Avalie', 'desc' => 'Partilhe a sua experiência e ajude outros viajantes a escolher.'],
                    ['num' => '4', 'icon' => 'telephone-fill', 'color' => '#8b5cf6', 'bg' => '#f5f3ff',
                     'title' => 'Contacte', 'desc' => 'Contacte directamente o hotel sem comissões ou taxas extra.'],
                ];
            @endphp
            @foreach($steps as $step)
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white rounded-4 p-4 shadow-sm h-100 position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle">
                            <span class="badge rounded-pill fw-bold px-3"
                                  style="background:{{ $step['color'] }};font-size:.85rem;">
                                {{ $step['num'] }}
                            </span>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mt-3 mb-3"
                             style="width:72px;height:72px;background:{{ $step['bg'] }};">
                            <i class="bi bi-{{ $step['icon'] }} fs-2" style="color:{{ $step['color'] }};"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $step['title'] }}</h5>
                        <p class="text-muted small mb-0">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── TESTEMUNHOS / REVIEWS RECENTES ── --}}
@php
    $recentReviews = \App\Models\Review::approved()
        ->with(['user', 'hotel'])
        ->latest()
        ->take(3)
        ->get();
@endphp

@if($recentReviews->isNotEmpty())
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2">O que dizem os hóspedes</h2>
            <p class="text-muted">Avaliações reais de hóspedes verificados</p>
        </div>
        <div class="row g-4">
            @foreach($recentReviews as $review)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                        <div class="d-flex gap-3 align-items-center mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:46px;height:46px;font-size:1.1rem;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $review->user->name }}</div>
                                <div class="text-warning small">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-quote fs-1 text-primary opacity-25"></i>
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="text-muted small mb-3 flex-grow-1">
                                "{{ Str::limit($review->comment, 120) }}"
                            </p>
                        @endif
                        <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                            <a href="{{ route('hotels.show', $review->hotel->slug) }}"
                               class="small text-decoration-none fw-semibold text-primary">
                                <i class="bi bi-building me-1"></i>{{ $review->hotel->name }}
                            </a>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── FAQ ── --}}
<section class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-4">
                <h2 class="fw-bold mb-2">Perguntas frequentes</h2>
                <p class="text-muted">Tudo o que precisa de saber sobre o HotelCompare</p>
                <a href="{{ route('hotels.index') }}" class="btn btn-primary rounded-pill px-4">
                    Ver hotéis <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="col-lg-8">
                <div class="accordion accordion-flush" id="faqAccordion">
                    @php
                        $faqs = [
                            ['q' => 'O HotelCompare é gratuito para turistas?',
                             'a' => 'Sim, completamente gratuito. Pode pesquisar, comparar e avaliar hotéis sem qualquer custo. Não cobramos comissões nem taxas de reserva.'],
                            ['q' => 'As informações dos hotéis são actualizadas com frequência?',
                             'a' => 'Sim! Os gestores dos hotéis actualizam a disponibilidade em tempo real através do nosso painel. As alterações são reflectidas imediatamente na plataforma.'],
                            ['q' => 'Como posso registar o meu hotel na plataforma?',
                             'a' => 'Crie uma conta como Gestor de Hotel, e o administrador da plataforma irá associar o seu hotel à sua conta. O processo é gratuito e sem comissões.'],
                            ['q' => 'As avaliações são verificadas?',
                             'a' => 'Sim. Todas as avaliações são revistas pela nossa equipa de moderação antes de serem publicadas, garantindo que são autênticas e respeitosas.'],
                            ['q' => 'Posso comparar hotéis de diferentes categorias?',
                             'a' => 'Sim! Pode comparar até 3 hotéis simultaneamente, independentemente do número de estrelas ou preço. Ideal para encontrar o melhor custo-benefício.'],
                        ];
                    @endphp
                    @foreach($faqs as $i => $faq)
                        <div class="accordion-item border-0 bg-transparent mb-2">
                            <h3 class="accordion-header">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} rounded-3 shadow-sm bg-white fw-semibold"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq{{ $i }}">
                                    {{ $faq['q'] }}
                                </button>
                            </h3>
                            <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted small bg-white rounded-bottom-3">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FOOTER CTA ── --}}
<section class="py-5 text-white" style="background:linear-gradient(135deg,#0d1b2a,#1a5276);">
    <div class="container text-center py-3">
        <i class="bi bi-building display-4 mb-3 d-block" style="color:rgba(255,255,255,.3);"></i>
        <h2 class="fw-bold mb-3">Tem um hotel em Benguela?</h2>
        <p class="fs-5 mb-4" style="color:rgba(255,255,255,.8);max-width:600px;margin:0 auto 1.5rem;">
            Junte-se ao HotelCompare e chegue a milhares de turistas.
            Gestão simples, actualizações em tempo real e sem comissões.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}"
               class="btn btn-warning btn-lg fw-bold px-5 rounded-pill">
                <i class="bi bi-building-add me-2"></i>Registar o meu hotel
            </a>
            <a href="{{ route('hotels.index') }}"
               class="btn btn-outline-light btn-lg px-5 rounded-pill">
                <i class="bi bi-search me-2"></i>Explorar hotéis
            </a>
        </div>
        <div class="mt-3 small" style="color:rgba(255,255,255,.5);">
            Gratuito · Sem comissões · Controlo total · Actualizações em tempo real
        </div>
    </div>
</section>

@endsection