@extends('layouts.app')

@section('title', $hotel->name . ' — HotelCompare')

@section('content')

{{-- ── CABEÇALHO DO HOTEL ── --}}
<section class="py-4" style="background: linear-gradient(135deg, #0d1b2a, #1a5276);">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Início</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}" class="text-white-50">Hotéis</a></li>
                <li class="breadcrumb-item active text-white">{{ $hotel->name }}</li>
            </ol>
        </nav>

        <div class="row align-items-end g-3">
            <div class="col-lg-8">
                <div class="stars mb-1">{{ $hotel->stars_label }}</div>
                <h1 class="text-white fw-bold mb-2">{{ $hotel->name }}</h1>
                <p class="text-white-75 mb-3">
                    <i class="bi bi-geo-alt me-1"></i>{{ $hotel->address }}, {{ $hotel->neighborhood ?? $hotel->city }}
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('hotels.compare', ['ids' => $hotel->id]) }}"
                       class="btn btn-sm btn-outline-light">
                        <i class="bi bi-arrow-left-right me-1"></i>Comparar
                    </a>
                    @if($hotel->phone)
                        <a href="tel:{{ $hotel->phone }}" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-telephone me-1"></i>{{ $hotel->phone }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                @if($hotel->avg_rating > 0)
                    <div class="d-inline-block bg-white rounded-3 px-4 py-2 text-center">
                        <div class="display-6 fw-bold" style="color:#1a5276;">
                            {{ number_format($hotel->avg_rating, 1) }}
                        </div>
                        <div class="small text-muted">{{ $hotel->total_reviews }} avaliações</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row g-4">

        {{-- ── COLUNA PRINCIPAL ── --}}
        <div class="col-lg-8">

            {{-- Galeria --}}
            @if($hotel->images->isNotEmpty())
                <div class="row g-2 mb-4">
                    <div class="col-8">
                        <img src="{{ $hotel->images->first()->url }}"
                             class="img-fluid rounded-3 w-100"
                             style="height:320px;object-fit:cover;"
                             alt="{{ $hotel->name }}">
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column gap-2">
                            @foreach($hotel->images->skip(1)->take(2) as $img)
                                <img src="{{ $img->url }}"
                                     class="img-fluid rounded-3 w-100"
                                     style="height:154px;object-fit:cover;"
                                     alt="">
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <img src="{{ $hotel->cover_image_url }}"
                     class="img-fluid rounded-3 w-100 mb-4"
                     style="height:320px;object-fit:cover;"
                     alt="{{ $hotel->name }}">
            @endif

            {{-- Descrição --}}
            @if($hotel->description)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Sobre o hotel</h5>
                        <p class="text-muted mb-0">{{ $hotel->description }}</p>
                    </div>
                </div>
            @endif

            {{-- Comodidades --}}
            @if($hotel->amenities->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Comodidades</h5>
                        <div class="row g-2">
                            @foreach($hotel->amenities as $amenity)
                                <div class="col-6 col-md-4">
                                    <div class="d-flex align-items-center gap-2 small">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        {{ $amenity->name }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Quartos com disponibilidade em tempo real --}}
            @if($hotel->rooms->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Quartos disponíveis</h5>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>
                                Actualizado em tempo real
                            </span>
                        </div>

                        <div class="row g-3" id="roomsContainer">
                            @foreach($hotel->rooms as $room)
                                <div class="col-md-6" id="room-{{ $room->id }}">
                                    <div class="border rounded-3 p-3 h-100 {{ $room->is_available ? '' : 'opacity-50' }}">
                                        @if($room->cover_image)
                                            <img src="{{ $room->cover_image_url }}"
                                                 class="img-fluid rounded-2 mb-2 w-100"
                                                 style="height:120px;object-fit:cover;"
                                                 alt="{{ $room->name }}">
                                        @endif
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-semibold mb-0">{{ $room->name }}</h6>
                                                <small class="text-muted">{{ $room->getTypeLabel() }}</small>
                                            </div>
                                            <span class="availability-badge {{ $room->is_available ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}"
                                                  id="badge-{{ $room->id }}">
                                                {{ $room->is_available ? 'Disponível' : 'Esgotado' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 small text-muted d-flex gap-3 flex-wrap">
                                            <span><i class="bi bi-people me-1"></i>{{ $room->max_guests }} hóspedes</span>
                                            <span><i class="bi bi-moon me-1"></i>{{ $room->beds }} cama{{ $room->beds > 1 ? 's' : '' }}</span>
                                            @if($room->has_ac)   <span><i class="bi bi-wind me-1"></i>AC</span> @endif
                                            @if($room->has_wifi) <span><i class="bi bi-wifi me-1"></i>Wi-Fi</span> @endif
                                        </div>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <span class="fw-bold" style="color:#f39c12;">
                                                {{ number_format($room->price_per_night, 0) }} Kz
                                                <span class="text-muted fw-normal small">/noite</span>
                                            </span>
                                            <small class="text-muted" id="units-{{ $room->id }}">
                                                {{ $room->available_units }}/{{ $room->total_units }} unidades
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Avaliações --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Avaliações dos hóspedes</h5>

                    @forelse($hotel->approvedReviews as $review)
                        <div class="d-flex gap-3 mb-4 pb-4 border-bottom">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                        justify-content-center flex-shrink-0"
                                 style="width:42px;height:42px;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong class="small">{{ $review->user->name }}</strong>
                                        <div class="stars small">
                                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                @if($review->title)
                                    <p class="fw-semibold small mb-1 mt-1">{{ $review->title }}</p>
                                @endif
                                @if($review->comment)
                                    <p class="small text-muted mb-0">{{ $review->comment }}</p>
                                @endif

                                {{-- Resposta do gestor --}}
                                @if($review->hasManagerReply())
                                    <div class="mt-3 p-3 rounded-3 border-start border-primary border-3"
                                         style="background:#f0f7ff;">
                                        <div class="small fw-semibold text-primary mb-1">
                                            <i class="bi bi-building me-1"></i>Resposta do hotel
                                            <span class="text-muted fw-normal ms-2">
                                                {{ $review->manager_replied_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <p class="small mb-0 text-dark">{{ $review->manager_reply }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small">Ainda não há avaliações aprovadas para este hotel.</p>
                    @endforelse

                    {{-- Formulário de avaliação --}}
                    @auth
                        @if(auth()->user()->isGuest())
                            <div class="border-top pt-4 mt-2">
                                <h6 class="fw-bold mb-3">Deixar avaliação</h6>
                                <form action="{{ route('hotels.reviews.store', $hotel) }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small">Classificação *</label>
                                            <select name="rating" class="form-select form-select-sm" required>
                                                <option value="">Seleccionar...</option>
                                                @foreach([5,4,3,2,1] as $r)
                                                    <option value="{{ $r }}">{{ str_repeat('★', $r) }} — {{ $r }} estrela{{ $r > 1 ? 's' : '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Data da estadia</label>
                                            <input type="date" name="stay_date" class="form-control form-control-sm"
                                                   max="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small">Título</label>
                                            <input type="text" name="title" class="form-control form-control-sm"
                                                   placeholder="Resumo da sua experiência" maxlength="100">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small">Comentário</label>
                                            <textarea name="comment" class="form-control form-control-sm"
                                                      rows="3" placeholder="Partilhe a sua experiência..."
                                                      maxlength="1000"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                                <i class="bi bi-send me-1"></i>Enviar avaliação
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="border-top pt-3 mt-2 text-center">
                            <p class="text-muted small mb-2">Faça login para deixar uma avaliação</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Entrar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- ── SIDEBAR DIREITA ── --}}
        <div class="col-lg-4">

            {{-- Card de contacto --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 sticky-top" style="top:80px;z-index:100;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Contactar o hotel</h6>

                    @if($hotel->price_per_night)
                        <div class="text-center mb-3 p-3 rounded-3 bg-light">
                            <div class="small text-muted">A partir de</div>
                            <div class="fw-bold fs-3" style="color:#f39c12;">
                                {{ number_format($hotel->price_per_night, 0) }} Kz
                            </div>
                            <div class="small text-muted">por noite</div>
                        </div>
                    @endif

                    <ul class="list-unstyled small mb-3">
                        @if($hotel->phone)
                            <li class="mb-2">
                                <i class="bi bi-telephone me-2 text-primary"></i>
                                <a href="tel:{{ $hotel->phone }}" class="text-decoration-none">{{ $hotel->phone }}</a>
                            </li>
                        @endif
                        @if($hotel->email)
                            <li class="mb-2">
                                <i class="bi bi-envelope me-2 text-primary"></i>
                                <a href="mailto:{{ $hotel->email }}" class="text-decoration-none">{{ $hotel->email }}</a>
                            </li>
                        @endif
                        @if($hotel->website)
                            <li class="mb-2">
                                <i class="bi bi-globe me-2 text-primary"></i>
                                <a href="{{ $hotel->website }}" target="_blank" class="text-decoration-none">Website</a>
                            </li>
                        @endif
                        <li>
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            {{ $hotel->address }}
                        </li>
                    </ul>

                    <a href="{{ route('hotels.compare', ['ids' => $hotel->id]) }}"
                       class="btn btn-outline-primary w-100 btn-sm">
                        <i class="bi bi-arrow-left-right me-1"></i>Adicionar à comparação
                    </a>
                </div>
            </div>

            {{-- Hotéis similares --}}
            @if($similar->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Hotéis similares</h6>
                        @foreach($similar as $s)
                            <a href="{{ route('hotels.show', $s->slug) }}"
                               class="d-flex gap-3 mb-3 text-decoration-none text-dark align-items-center">
                                <img src="{{ $s->cover_image_url }}"
                                     class="rounded-2 flex-shrink-0"
                                     style="width:60px;height:60px;object-fit:cover;"
                                     alt="{{ $s->name }}">
                                <div>
                                    <div class="small fw-semibold">{{ $s->name }}</div>
                                    <div class="stars" style="font-size:.7rem;">{{ $s->stars_label }}</div>
                                    @if($s->price_per_night)
                                        <div class="small" style="color:#f39c12;">
                                            {{ number_format($s->price_per_night, 0) }} Kz/noite
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function initEcho() {
        const hotelId = @json($hotel->id);

        window.Echo.channel(`hotel.${hotelId}`)
            .listen('.room.availability', (data) => {
                console.log('✅ Evento recebido:', data);

                const badge = document.getElementById(`badge-${data.room_id}`);
                const units = document.getElementById(`units-${data.room_id}`);
                const card  = document.querySelector(`#room-${data.room_id} > div`);

                if (badge) {
                    badge.textContent = data.is_available ? 'Disponível' : 'Esgotado';
                    badge.className   = `availability-badge ${data.is_available
                        ? 'bg-success-subtle text-success border border-success-subtle'
                        : 'bg-danger-subtle text-danger border border-danger-subtle'}`;
                }
                if (units) {
                    units.textContent = `${data.available_units}/${data.total_units} unidades`;
                }
                if (card) {
                    card.classList.toggle('opacity-50', !data.is_available);
                }
            });
    }

    // Espera que o Echo esteja disponível (carregado pelo app.js)
    function waitForEcho(attempts = 0) {
        if (typeof window.Echo !== 'undefined') {
            initEcho();
        } else if (attempts < 20) {
            setTimeout(() => waitForEcho(attempts + 1), 200);
        } else {
            console.error('❌ Echo não carregou após 4 segundos.');
        }
    }

    document.addEventListener('DOMContentLoaded', () => waitForEcho());
</script>
@endpush