<div>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
</div>

@extends('layouts.dashboard')

@section('title', 'Avaliações')
@section('page-title', 'Avaliações do Hotel')

@section('content')

@if($reviews->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-star display-1 text-muted"></i>
        <h5 class="mt-3 text-muted">Ainda não há avaliações</h5>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($reviews as $review)
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">

                    {{-- Cabeçalho --}}
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                        justify-content-center flex-shrink-0"
                                 style="width:42px;height:42px;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $review->user->name }}</div>
                                <div class="stars small">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                                <div class="text-muted small">{{ $review->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        <span class="badge rounded-pill
                            {{ $review->status === 'approved' ? 'bg-success' :
                               ($review->status === 'pending'  ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ match($review->status) {
                                'approved' => 'Aprovada',
                                'pending'  => 'Aguarda moderação',
                                'rejected' => 'Rejeitada',
                                default    => $review->status
                            } }}
                        </span>
                    </div>

                    {{-- Conteúdo --}}
                    @if($review->title)
                        <h6 class="fw-semibold mb-1">{{ $review->title }}</h6>
                    @endif
                    @if($review->comment)
                        <p class="text-muted small mb-0">{{ $review->comment }}</p>
                    @endif

                    {{-- Resposta existente --}}
                    @if($review->hasManagerReply())
                        <div class="mt-3 p-3 rounded-3 border-start border-primary border-3"
                             style="background:#f0f7ff;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="small fw-semibold text-primary">
                                    <i class="bi bi-building me-1"></i>Resposta do hotel
                                    <span class="text-muted fw-normal ms-2">
                                        {{ $review->manager_replied_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                <form action="{{ route('manager.reviews.reply.delete', $review) }}"
                                      method="POST"
                                      onsubmit="return confirm('Remover esta resposta?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="font-size:.7rem;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="small mb-0 text-dark">{{ $review->manager_reply }}</p>
                        </div>
                    @endif

                    {{-- Formulário de resposta --}}
                    @if($review->isApproved())
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#reply-{{ $review->id }}">
                                <i class="bi bi-reply me-1"></i>
                                {{ $review->hasManagerReply() ? 'Editar resposta' : 'Responder' }}
                            </button>

                            <div class="collapse mt-2" id="reply-{{ $review->id }}">
                                <form action="{{ route('manager.reviews.reply', $review) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="manager_reply"
                                                  class="form-control form-control-sm"
                                                  rows="3"
                                                  maxlength="1000"
                                                  placeholder="Escreva a sua resposta...">{{ $review->manager_reply }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-send me-1"></i>Publicar resposta
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
@endif

@endsection