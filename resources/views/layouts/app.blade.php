<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HotelCompare Benguela')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --hc-primary:   #1a5276;
            --hc-accent:    #f39c12;
            --hc-light:     #f8f9fa;
            --hc-dark:      #0d1b2a;
        }

        body { font-family: 'Segoe UI', sans-serif; background: var(--hc-light); }

        /* ── Navbar ── */
        .navbar-brand span { color: var(--hc-accent); }
        .navbar { background: var(--hc-dark) !important; }
        .navbar .nav-link { color: rgba(255,255,255,.8) !important; transition: color .2s; }
        .navbar .nav-link:hover,
        .navbar .nav-link.active { color: var(--hc-accent) !important; }

        /* ── Botões ── */
        .btn-primary   { background: var(--hc-primary); border-color: var(--hc-primary); }
        .btn-primary:hover { background: #154360; border-color: #154360; }
        .btn-accent    { background: var(--hc-accent); border-color: var(--hc-accent); color:#fff; }
        .btn-accent:hover  { background: #d68910; border-color: #d68910; color:#fff; }

        /* ── Cards de hotel ── */
        .hotel-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.08);
                      transition: transform .2s, box-shadow .2s; overflow: hidden; }
        .hotel-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.14); }
        .hotel-card img   { height: 200px; object-fit: cover; width: 100%; }

        /* ── Stars ── */
        .stars { color: var(--hc-accent); letter-spacing: 1px; }

        /* ── Badge disponibilidade ── */
        .availability-badge { font-size: .75rem; padding: .3rem .6rem; border-radius: 20px; }

        /* ── Footer ── */
        footer { background: var(--hc-dark); color: rgba(255,255,255,.7); }
        footer a { color: var(--hc-accent); text-decoration: none; }

        /* ── Alertas flash ── */
        .flash-container { position: fixed; top: 70px; right: 16px; z-index: 9999; width: 340px; }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
            <i class="bi bi-building me-1"></i>Hotel<span>Compare</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('hotels.*') ? 'active' : '' }}"
                       href="{{ route('hotels.index') }}">Hotéis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('hotels.compare') ? 'active' : '' }}"
                       href="{{ route('hotels.compare') }}">Comparar</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-1">
                @auth
                    {{-- Dashboard conforme role --}}
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-shield-check me-1"></i>Admin
                            </a>
                        </li>
                    @elseif(auth()->user()->isHotelManager())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('manager.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Painel
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-accent btn-sm px-3" href="{{ route('register') }}">Registar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- ── FLASH MESSAGES ── --}}
<div class="flash-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible shadow-sm fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible shadow-sm fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible shadow-sm fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- ── CONTEÚDO PRINCIPAL ── --}}
<main>
    @yield('content')
</main>

{{-- ── FOOTER ── --}}
<footer class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold text-white mb-3">
                    <i class="bi bi-building me-1"></i>HotelCompare
                </h5>
                <p class="small">A plataforma de comparação de hotéis em Benguela, Angola.
                   Encontre e compare os melhores hotéis com informações actualizadas em tempo real.</p>
            </div>
            <div class="col-md-2">
                <h6 class="text-white mb-3">Navegação</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}">Início</a></li>
                    <li><a href="{{ route('hotels.index') }}">Hotéis</a></li>
                    <li><a href="{{ route('hotels.compare') }}">Comparar</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white mb-3">Para Hotéis</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('register') }}">Registar o seu hotel</a></li>
                    <li><a href="{{ route('login') }}">Área do gestor</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white mb-3">Contacto</h6>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-geo-alt me-2"></i>Benguela, Angola</li>
                    <li><i class="bi bi-envelope me-2"></i>info@hotelcompare.ao</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center small mb-0">
            &copy; {{ date('Y') }} HotelCompare &mdash; Universidade Katyavala Bwila
        </p>
    </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Auto-fechar alertas após 5 segundos --}}
<script>
    setTimeout(() => {
        document.querySelectorAll('.flash-container .alert').forEach(el => {
            bootstrap.Alert.getOrCreateInstance(el).close();
        });
    }, 5000);
</script>

@stack('scripts')
@vite(['resources/js/app.js'])
</body>
</html>