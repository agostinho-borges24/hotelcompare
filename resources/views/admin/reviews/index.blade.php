@extends('layouts.dashboard')

@section('title', 'Moderação de Avaliações')
@section('page-title', 'Avaliações')

@section('content')

{{-- Filtros --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.reviews.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos os estados</option>
                        <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pendentes</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovadas</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitadas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Lista --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Utilizador</th>
                    <th>Hotel</th>
                    <th>Classificação</th>
                    <th>Comentário</th>
                    <th>Data</th>
                    <th>Estado</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td class="small fw-semibold">{{ $review->user->name }}</td>
                        <td class="small text-muted">{{ $review->hotel->name }}</td>
                        <td>
                            <span class="stars" style="font-size:.8rem;">
                                {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                            </span>
                        </td>
                        <td class="small text-muted" style="max-width:220px;">
                            @if($review->title)
                                <div class="fw-semibold text-dark">{{ $review->title }}</div>
                            @endif
                            {{ Str::limit($review->comment, 80) }}
                        </td>
                        <td class="small text-muted">{{ $review->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge rounded-pill
                                {{ $review->status === 'approved' ? 'bg-success' :
                                   ($review->status === 'pending'  ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ match($review->status) {
                                    'approved' => 'Aprovada',
                                    'pending'  => 'Pendente',
                                    'rejected' => 'Rejeitada',
                                    default    => $review->status
                                } }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($review->status !== 'approved')
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-success" title="Aprovar">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($review->status !== 'rejected')
                                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-warning" title="Rejeitar">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                      onsubmit="return confirm('Eliminar esta avaliação?')">
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
                        <td colspan="7" class="text-center py-4 text-muted">Nenhuma avaliação encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

@endsection