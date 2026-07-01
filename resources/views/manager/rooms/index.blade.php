@extends('layouts.dashboard')

@section('title', 'Gestão de Quartos')
@section('page-title', 'Quartos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted small mb-0">Gerir quartos e disponibilidade em tempo real</p>
    <a href="{{ route('manager.quartos.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Adicionar quarto
    </a>
</div>

@if($rooms->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-door-open display-1 text-muted"></i>
        <h5 class="mt-3 text-muted">Ainda não tem quartos registados</h5>
        <a href="{{ route('manager.quartos.create') }}" class="btn btn-primary btn-sm mt-2">
            Adicionar primeiro quarto
        </a>
    </div>
@else
    <div class="row g-3">
        @foreach($rooms as $room)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-3 h-100" id="room-card-{{ $room->id }}">

                    @if($room->cover_image)
                        <img src="{{ $room->cover_image_url }}"
                             class="card-img-top"
                             style="height:140px;object-fit:cover;"
                             alt="{{ $room->name }}">
                    @endif

                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $room->name }}</h6>
                                <small class="text-muted">{{ $room->getTypeLabel() }}</small>
                            </div>
                            <span class="badge {{ $room->is_available ? 'bg-success' : 'bg-danger' }}"
                                  id="badge-{{ $room->id }}">
                                {{ $room->is_available ? 'Disponível' : 'Esgotado' }}
                            </span>
                        </div>

                        <div class="d-flex gap-3 small text-muted mb-3">
                            <span><i class="bi bi-people me-1"></i>{{ $room->max_guests }} hóspedes</span>
                            <span><i class="bi bi-moon me-1"></i>{{ $room->beds }} cama{{ $room->beds > 1 ? 's' : '' }}</span>
                        </div>

                        <div class="fw-bold mb-3" style="color:#f39c12;">
                            {{ number_format($room->price_per_night, 0) }} Kz
                            <span class="text-muted fw-normal small">/noite</span>
                        </div>

                        {{-- Controlo de disponibilidade em tempo real --}}
                        <div class="border rounded-3 p-3 bg-light mb-3">
                            <label class="form-label small fw-semibold mb-2">
                                Unidades disponíveis
                                <span class="text-muted fw-normal">(de {{ $room->total_units }})</span>
                            </label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="range" class="form-range flex-grow-1"
                                       min="0" max="{{ $room->total_units }}"
                                       value="{{ $room->available_units }}"
                                       id="range-{{ $room->id }}"
                                       oninput="updateUnits({{ $room->id }}, this.value)">
                                <span class="badge bg-primary px-2 py-1"
                                      id="units-label-{{ $room->id }}"
                                      style="min-width:40px;text-align:center;">
                                    {{ $room->available_units }}
                                </span>
                            </div>
                            <button class="btn btn-sm btn-outline-primary w-100 mt-2"
                                    onclick="saveAvailability({{ $room->id }}, {{ $room->total_units }})">
                                <i class="bi bi-lightning me-1"></i>Actualizar disponibilidade
                            </button>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('manager.quartos.edit', $room) }}"
                               class="btn btn-outline-secondary btn-sm flex-grow-1">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <form action="{{ route('manager.quartos.destroy', $room) }}" method="POST"
                                  onsubmit="return confirm('Tem a certeza que quer remover este quarto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateUnits(roomId, value) {
        document.getElementById(`units-label-${roomId}`).textContent = value;
    }

    async function saveAvailability(roomId, totalUnits) {
        const units = parseInt(document.getElementById(`range-${roomId}`).value);

        try {
            const res = await fetch(`/gestor/quartos/${roomId}/disponibilidade`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ available_units: units }),
            });

            const data = await res.json();

            if (data.success) {
                const badge = document.getElementById(`badge-${roomId}`);
                badge.textContent  = data.is_available ? 'Disponível' : 'Esgotado';
                badge.className    = `badge ${data.is_available ? 'bg-success' : 'bg-danger'}`;

                // Feedback visual
                const card = document.getElementById(`room-card-${roomId}`);
                card.style.outline = '2px solid #27ae60';
                setTimeout(() => card.style.outline = '', 1500);
            }
        } catch (err) {
            alert('Erro ao actualizar disponibilidade. Tente novamente.');
        }
    }
</script>
@endpush