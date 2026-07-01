@extends('layouts.dashboard')

@section('title', 'Gestão de Hotéis')
@section('page-title', 'Hotéis')

@section('content')

{{-- Filtros --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.hoteis.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Pesquisar por nome..." value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos os estados</option>
                        <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Activos</option>
                        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pendentes</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspensos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.hoteis.create') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-plus-circle me-1"></i>Novo
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Hotel</th>
                    <th>Gestor</th>
                    <th>Estrelas</th>
                    <th>Preço/noite</th>
                    <th>Avaliação</th>
                    <th>Estado</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hotels as $hotel)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $hotel->cover_image_url }}"
                                     class="rounded-2 flex-shrink-0"
                                     style="width:44px;height:44px;object-fit:cover;" alt="">
                                <div>
                                    <div class="fw-semibold small">{{ $hotel->name }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $hotel->neighborhood ?? $hotel->city }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="small text-muted">{{ $hotel->manager?->name ?? '—' }}</td>
                        <td>
                            <span class="stars" style="font-size:.8rem;">{{ $hotel->stars_label }}</span>
                        </td>
                        <td class="small" style="color:#f39c12;">
                            {{ $hotel->price_per_night ? number_format($hotel->price_per_night, 0) . ' Kz' : '—' }}
                        </td>
                        <td>
                            @if($hotel->avg_rating > 0)
                                <span class="fw-semibold small">★ {{ number_format($hotel->avg_rating, 1) }}</span>
                                <span class="text-muted" style="font-size:.75rem;">({{ $hotel->total_reviews }})</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <select class="form-select form-select-sm"
                                    style="width:auto;"
                                    onchange="updateStatus({{ $hotel->id }}, this.value)">
                                <option value="active"    {{ $hotel->status === 'active'    ? 'selected' : '' }}>Activo</option>
                                <option value="pending"   {{ $hotel->status === 'pending'   ? 'selected' : '' }}>Pendente</option>
                                <option value="suspended" {{ $hotel->status === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                            </select>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.hoteis.show', $hotel) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.hoteis.edit', $hotel) }}"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.hoteis.destroy', $hotel) }}" method="POST"
                                      onsubmit="return confirm('Eliminar este hotel?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Nenhum hotel encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($hotels->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $hotels->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    async function updateStatus(hotelId, status) {
        await fetch(`/admin/hoteis/${hotelId}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ status }),
        });
    }
</script>
@endpush