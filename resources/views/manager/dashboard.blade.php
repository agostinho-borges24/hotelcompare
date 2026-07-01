@extends('layouts.dashboard')

@section('title', 'Dashboard — Gestor')
@section('page-title', 'Dashboard')

@section('content')

@if(! $hotel)
    <div class="text-center py-5">
        <i class="bi bi-building-add display-1 text-muted"></i>
        <h4 class="mt-3 fw-bold">Ainda não tem um hotel associado</h4>
        <p class="text-muted">Contacte o administrador para associar o seu hotel à conta.</p>
        <a href="mailto:admin@hotelcompare.ao" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-envelope me-1"></i>Contactar admin
        </a>
    </div>
@else

    {{-- ── ESTATÍSTICAS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small mb-1">Total de quartos</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_rooms'] }}</div>
                    </div>
                    <div class="icon-box" style="background:#e8f4fd;">
                        <i class="bi bi-door-open" style="color:#1a5276;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small mb-1">Quartos disponíveis</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['available_rooms'] }}</div>
                    </div>
                    <div class="icon-box" style="background:#eafaf1;">
                        <i class="bi bi-check-circle" style="color:#27ae60;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small mb-1">Avaliação média</div>
                        <div class="fs-3 fw-bold" style="color:#f39c12;">
                            {{ $stats['avg_rating'] > 0 ? number_format($stats['avg_rating'], 1) : '—' }}
                        </div>
                    </div>
                    <div class="icon-box" style="background:#fef9e7;">
                        <i class="bi bi-star" style="color:#f39c12;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small mb-1">Avaliações pendentes</div>
                        <div class="fs-3 fw-bold text-warning">{{ $stats['pending_reviews'] }}</div>
                    </div>
                    <div class="icon-box" style="background:#fef5e7;">
                        <i class="bi bi-clock" style="color:#e67e22;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── INFO DO HOTEL ── --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="fw-bold mb-0">O meu hotel</h6>
                        <a href="{{ route('manager.hotel.edit') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </a>
                    </div>

                    <img src="{{ $hotel->cover_image_url }}"
                         class="img-fluid rounded-3 w-100 mb-3"
                         style="height:160px;object-fit:cover;"
                         alt="{{ $hotel->name }}">

                    <h5 class="fw-bold mb-1">{{ $hotel->name }}</h5>
                    <div class="stars mb-2" style="color:#f39c12;">{{ $hotel->stars_label }}</div>

                    <ul class="list-unstyled small text-muted mb-3">
                        <li class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $hotel->address }}</li>
                        @if($hotel->phone)
                            <li class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $hotel->phone }}</li>
                        @endif
                        @if($hotel->email)
                            <li><i class="bi bi-envelope me-2"></i>{{ $hotel->email }}</li>
                        @endif
                    </ul>

                    <div class="d-flex gap-2">
                        <span class="badge {{ $hotel->isActive() ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($hotel->status) }}
                        </span>
                        @if($hotel->is_featured)
                            <span class="badge" style="background:#f39c12;">Destaque</span>
                        @endif
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('hotels.show', $hotel->slug) }}" target="_blank"
                           class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-eye me-1"></i>Ver página pública
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── AVALIAÇÕES RECENTES ── --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Avaliações recentes</h6>

                    @forelse($recentReviews as $review)
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                        justify-content-center flex-shrink-0"
                                 style="width:36px;height:36px;font-size:.8rem;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong class="small">{{ $review->user->name }}</strong>
                                    <span class="badge {{ $review->status === 'approved' ? 'bg-success' : ($review->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}" style="font-size:.65rem;">
                                        {{ match($review->status) { 'approved' => 'Aprovada', 'pending' => 'Pendente', default => 'Rejeitada' } }}
                                    </span>
                                </div>
                                <div style="color:#f39c12;font-size:.8rem;">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                                @if($review->comment)
                                    <p class="small text-muted mb-0 mt-1">
                                        {{ Str::limit($review->comment, 100) }}
                                    </p>
                                @endif
                                <div class="small text-muted mt-1">{{ $review->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-star display-4"></i>
                            <p class="small mt-2">Ainda não há avaliações.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── QUARTOS ── --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Quartos</h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('manager.quartos.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Novo quarto
                            </a>
                            <a href="{{ route('manager.quartos.index') }}" class="btn btn-sm btn-outline-secondary">
                                Ver todos
                            </a>
                        </div>
                    </div>

                    @if($hotel->rooms->isEmpty())
                        <p class="text-muted small text-center py-3">
                            Ainda não adicionou quartos.
                            <a href="{{ route('manager.quartos.create') }}">Adicionar agora</a>
                        </p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Quarto</th>
                                        <th>Tipo</th>
                                        <th>Preço/noite</th>
                                        <th>Disponibilidade</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hotel->rooms->take(5) as $room)
                                        <tr>
                                            <td class="fw-semibold small">{{ $room->name }}</td>
                                            <td class="small text-muted">{{ $room->getTypeLabel() }}</td>
                                            <td class="small" style="color:#f39c12;">
                                                {{ number_format($room->price_per_night, 0) }} Kz
                                            </td>
                                            <td>
                                                <span class="badge {{ $room->is_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $room->available_units }}/{{ $room->total_units }}
                                                    {{ $room->is_available ? 'disponível' : 'esgotado' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('manager.quartos.edit', $room) }}"
                                                   class="btn btn-sm btn-outline-secondary py-0 px-2">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endif

@endsection