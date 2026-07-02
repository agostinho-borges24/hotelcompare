@extends('layouts.dashboard')

@section('title', $hotel->name)
@section('page-title', 'Detalhe do Hotel')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.hoteis.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.hoteis.edit', $hotel) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <form action="{{ route('admin.hoteis.destroy', $hotel) }}" method="POST"
              onsubmit="return confirm('Tem a certeza que quer eliminar este hotel?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">
                <i class="bi bi-trash me-1"></i>Eliminar
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                @if($hotel->cover_image)
                    <img src="{{ $hotel->cover_image_url }}"
                         class="img-fluid rounded-3 w-100 mb-4"
                         style="height:220px;object-fit:cover;" alt="{{ $hotel->name }}">
                @endif
                <div class="stars mb-1">{{ $hotel->stars_label }}</div>
                <h4 class="fw-bold mb-1">{{ $hotel->name }}</h4>
                <p class="text-muted small mb-3">
                    <i class="bi bi-geo-alt me-1"></i>{{ $hotel->address }}{{ $hotel->neighborhood ? ', ' . $hotel->neighborhood : '' }}
                </p>
                @if($hotel->description)
                    <p class="text-muted small">{{ $hotel->description }}</p>
                @endif

                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <div class="small text-muted fw-semibold">Telefone</div>
                        <div class="small">{{ $hotel->phone ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted fw-semibold">Email</div>
                        <div class="small">{{ $hotel->email ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted fw-semibold">Preço/noite</div>
                        <div class="small fw-bold" style="color:#f39c12;">
                            {{ $hotel->price_per_night ? number_format($hotel->price_per_night, 0) . ' Kz' : '—' }}
                        </div>
                    </div>
                </div>

                @if($hotel->amenities->isNotEmpty())
                    <hr>
                    <div class="small text-muted fw-semibold mb-2">Comodidades</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($hotel->amenities as $a)
                            <span class="badge bg-light text-secondary border">{{ $a->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Quartos --}}
        @if($hotel->rooms->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Quartos ({{ $hotel->rooms->count() }})</h6>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Nome</th><th>Tipo</th><th>Preço/noite</th><th>Unidades</th><th>Estado</th></tr></thead>
                            <tbody>
                                @foreach($hotel->rooms as $room)
                                    <tr>
                                        <td class="small fw-semibold">{{ $room->name }}</td>
                                        <td class="small text-muted">{{ $room->getTypeLabel() }}</td>
                                        <td class="small" style="color:#f39c12;">{{ number_format($room->price_per_night, 0) }} Kz</td>
                                        <td class="small">{{ $room->available_units }}/{{ $room->total_units }}</td>
                                        <td><span class="badge {{ $room->is_available ? 'bg-success' : 'bg-danger' }}">{{ $room->is_available ? 'Disponível' : 'Esgotado' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Avaliações aprovadas --}}
        @if($hotel->approvedReviews->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Avaliações aprovadas ({{ $hotel->approvedReviews->count() }})</h6>
                    @foreach($hotel->approvedReviews->take(5) as $review)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between">
                                <strong class="small">{{ $review->user->name }}</strong>
                                <span class="stars small">{{ str_repeat('★', $review->rating) }}</span>
                            </div>
                            @if($review->comment)
                                <p class="small text-muted mb-0 mt-1">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Estado --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Estado do Hotel</h6>
                <form action="{{ route('admin.hotels.status', $hotel) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm mb-2">
                        <option value="active"    {{ $hotel->status === 'active'    ? 'selected' : '' }}>Activo</option>
                        <option value="pending"   {{ $hotel->status === 'pending'   ? 'selected' : '' }}>Pendente</option>
                        <option value="suspended" {{ $hotel->status === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Actualizar estado</button>
                </form>
            </div>
        </div>

        {{-- Gestor --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Gestor</h6>
                @if($hotel->manager)
                    <div class="fw-semibold small">{{ $hotel->manager->name }}</div>
                    <div class="text-muted small">{{ $hotel->manager->email }}</div>
                @else
                    <p class="text-muted small">Sem gestor associado.</p>
                @endif
            </div>
        </div>

        {{-- Estatísticas --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Estatísticas</h6>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Avaliação média</span>
                    <span class="fw-bold" style="color:#f39c12;">
                        {{ $hotel->avg_rating > 0 ? '★ ' . number_format($hotel->avg_rating, 1) : '—' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Total de avaliações</span>
                    <span class="fw-bold">{{ $hotel->total_reviews }}</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Total de quartos</span>
                    <span class="fw-bold">{{ $hotel->rooms->count() }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Imagens</span>
                    <span class="fw-bold">{{ $hotel->images->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection