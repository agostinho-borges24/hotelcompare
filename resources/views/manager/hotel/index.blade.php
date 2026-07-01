@extends('layouts.dashboard')

@section('title', 'O Meu Hotel')
@section('page-title', 'O Meu Hotel')

@section('content')

<div class="row g-4">

    {{-- ── INFO GERAL ── --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0">Informações do Hotel</h6>
                    <a href="{{ route('manager.hotel.edit') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small text-muted fw-semibold mb-1">Nome</div>
                        <div>{{ $hotel->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted fw-semibold mb-1">Estrelas</div>
                        <div class="stars">{{ $hotel->stars_label }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted fw-semibold mb-1">Preço mínimo/noite</div>
                        <div style="color:#f39c12;font-weight:600;">
                            {{ $hotel->price_per_night ? number_format($hotel->price_per_night, 0) . ' Kz' : '—' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted fw-semibold mb-1">Estado</div>
                        <span class="badge {{ $hotel->status === 'active' ? 'bg-success' : ($hotel->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ match($hotel->status) {
                                'active'    => 'Activo',
                                'pending'   => 'Aguarda aprovação',
                                'suspended' => 'Suspenso',
                                default     => $hotel->status
                            } }}
                        </span>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted fw-semibold mb-1">Morada</div>
                        <div>{{ $hotel->address }}{{ $hotel->neighborhood ? ', ' . $hotel->neighborhood : '' }}</div>
                    </div>
                    @if($hotel->description)
                        <div class="col-12">
                            <div class="small text-muted fw-semibold mb-1">Descrição</div>
                            <div class="small">{{ $hotel->description }}</div>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <div class="small text-muted fw-semibold mb-1">Telefone</div>
                        <div>{{ $hotel->phone ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted fw-semibold mb-1">Email</div>
                        <div>{{ $hotel->email ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted fw-semibold mb-1">Website</div>
                        <div>{{ $hotel->website ?? '—' }}</div>
                    </div>
                </div>

                {{-- Comodidades --}}
                @if($hotel->amenities->isNotEmpty())
                    <hr>
                    <div class="small text-muted fw-semibold mb-2">Comodidades</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($hotel->amenities as $amenity)
                            <span class="badge bg-light text-secondary border">
                                <i class="bi bi-check-circle text-success me-1"></i>{{ $amenity->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── QUARTOS RESUMO ── --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Quartos</h6>
                    <a href="{{ route('manager.quartos.index') }}" class="btn btn-sm btn-outline-primary">
                        Gerir
                    </a>
                </div>
                <div class="text-center py-2">
                    <div class="display-6 fw-bold text-primary">{{ $hotel->rooms->count() }}</div>
                    <div class="small text-muted">tipos de quarto</div>
                </div>
                <div class="d-flex justify-content-around mt-2">
                    <div class="text-center">
                        <div class="fw-bold text-success">{{ $hotel->rooms->where('is_available', true)->count() }}</div>
                        <div class="small text-muted">Disponíveis</div>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold text-danger">{{ $hotel->rooms->where('is_available', false)->count() }}</div>
                        <div class="small text-muted">Esgotados</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Avaliação --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4 text-center">
                <h6 class="fw-bold mb-3">Avaliação</h6>
                <div class="display-5 fw-bold" style="color:#f39c12;">
                    {{ $hotel->avg_rating > 0 ? number_format($hotel->avg_rating, 1) : '—' }}
                </div>
                <div class="stars my-1">
                    @if($hotel->avg_rating > 0)
                        {{ str_repeat('★', round($hotel->avg_rating)) }}{{ str_repeat('☆', 5 - round($hotel->avg_rating)) }}
                    @endif
                </div>
                <div class="small text-muted">{{ $hotel->total_reviews }} avaliações aprovadas</div>
            </div>
        </div>
    </div>

    {{-- ── GALERIA ── --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Galeria de Imagens</h6>
                </div>

                {{-- Upload --}}
                <form action="{{ route('manager.hotel.images.upload') }}" method="POST"
                      enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="border border-dashed rounded-3 p-4 text-center"
                         style="border-style:dashed!important;cursor:pointer;"
                         onclick="document.getElementById('imageUpload').click()">
                        <i class="bi bi-cloud-upload display-4 text-muted"></i>
                        <p class="text-muted small mb-2 mt-2">
                            Clique para seleccionar imagens (JPG, PNG, WEBP — máx. 3MB cada)
                        </p>
                        <input type="file" id="imageUpload" name="images[]"
                               multiple accept="image/*" class="d-none"
                               onchange="this.form.submit()">
                    </div>
                </form>

                {{-- Grid de imagens --}}
                @if($hotel->images->isNotEmpty())
                    <div class="row g-3">
                        @foreach($hotel->images as $image)
                            <div class="col-6 col-md-3 col-lg-2">
                                <div class="position-relative rounded-3 overflow-hidden"
                                     style="height:100px;">
                                    <img src="{{ $image->url }}"
                                         class="w-100 h-100 object-fit-cover"
                                         alt="">
                                    <form action="{{ route('manager.hotel.images.delete', $image) }}"
                                          method="POST"
                                          onsubmit="return confirm('Remover esta imagem?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-1 lh-1"
                                                style="font-size:.7rem;">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small text-center">Ainda não tem imagens na galeria.</p>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection