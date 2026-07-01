@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── STATS ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Total de Hotéis</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_hotels'] }}</div>
                    <div class="small text-success mt-1">{{ $stats['active_hotels'] }} activos</div>
                </div>
                <div class="icon-box bg-primary bg-opacity-10">
                    <i class="bi bi-building text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Aguardam Aprovação</div>
                    <div class="fs-3 fw-bold text-warning">{{ $stats['pending_hotels'] }}</div>
                    <div class="small text-muted mt-1">hotéis pendentes</div>
                </div>
                <div class="icon-box bg-warning bg-opacity-10">
                    <i class="bi bi-clock text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Utilizadores</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_users'] }}</div>
                    <div class="small text-muted mt-1">{{ $stats['total_managers'] }} gestores</div>
                </div>
                <div class="icon-box bg-info bg-opacity-10">
                    <i class="bi bi-people text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Avaliações</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_reviews'] }}</div>
                    <div class="small text-warning mt-1">{{ $stats['pending_reviews'] }} pendentes</div>
                </div>
                <div class="icon-box" style="background:#fef9e7;">
                    <i class="bi bi-star" style="color:#f39c12;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ── HOTÉIS RECENTES ── --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Hotéis Recentes</h6>
                    <a href="{{ route('admin.hoteis.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver todos
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Hotel</th>
                                <th>Gestor</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentHotels as $hotel)
                                <tr>
                                    <td>
                                        <div class="fw-semibold small">{{ $hotel->name }}</div>
                                        <div class="stars" style="font-size:.7rem;">{{ $hotel->stars_label }}</div>
                                    </td>
                                    <td class="small text-muted">{{ $hotel->manager?->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge rounded-pill
                                            {{ $hotel->status === 'active' ? 'bg-success' :
                                               ($hotel->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ match($hotel->status) {
                                                'active'    => 'Activo',
                                                'pending'   => 'Pendente',
                                                'suspended' => 'Suspenso',
                                                default     => $hotel->status
                                            } }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.hoteis.show', $hotel) }}"
                                           class="btn btn-xs btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── AVALIAÇÕES PENDENTES ── --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Avaliações Pendentes</h6>
                    <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
                       class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>

                @forelse($pendingReviews as $review)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <strong class="small">{{ $review->user->name }}</strong>
                                <div class="stars" style="font-size:.75rem;">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-xs btn-success btn-sm" title="Aprovar">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-xs btn-danger btn-sm" title="Rejeitar">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="small text-muted">
                            <i class="bi bi-building me-1"></i>{{ $review->hotel->name }}
                        </div>
                        @if($review->comment)
                            <p class="small text-muted mb-0 mt-1">
                                {{ Str::limit($review->comment, 70) }}
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-check-circle display-4 text-success"></i>
                        <p class="small mt-2">Sem avaliações pendentes.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection