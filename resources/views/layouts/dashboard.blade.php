<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') — HotelCompare</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --hc-primary: #1a5276;
            --hc-accent:  #f39c12;
            --hc-dark:    #0d1b2a;
            --sidebar-w:  260px;
        }

        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--hc-dark);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: transform .3s;
        }
        .sidebar .brand {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar .brand span { color: var(--hc-accent); }
        .sidebar .nav-link {
            color: rgba(255,255,255,.7);
            padding: .6rem 1.5rem;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: .9rem;
            transition: all .2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        .sidebar .nav-link.active { border-left: 3px solid var(--hc-accent); }
        .sidebar .nav-section {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,.35);
            padding: 1rem 1.5rem .3rem;
        }

        /* ── Main content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: .75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        /* ── Stat cards ── */
        .stat-card {
            border: none;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }
        .stat-card .icon-box {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* ── Tables ── */
        .table th { font-size: .8rem; text-transform: uppercase;
                    letter-spacing: .5px; color: #6c757d; }

        /* ── Mobile sidebar toggle ── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }

        /* ── Flash ── */
        .flash-container { position: fixed; top: 16px; right: 16px; z-index: 9999; width: 340px; }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar" id="sidebar">
    <div class="brand">
        <a href="{{ route('home') }}" class="text-decoration-none">
            <span class="fw-bold fs-5 text-white">Hotel<span>Compare</span></span>
        </a>
        <div class="small text-white-50 mt-1">
            @if(auth()->user()->isAdmin())
                <i class="bi bi-shield-check me-1"></i>Administrador
            @else
                <i class="bi bi-building me-1"></i>Gestor de Hotel
            @endif
        </div>
    </div>

    <nav class="py-2">
        @if(auth()->user()->isAdmin())
            {{-- Menu Admin --}}
            <div class="nav-section">Principal</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>

            <div class="nav-section">Gestão</div>
            <a href="{{ route('admin.hoteis.index') }}"
               class="nav-link {{ request()->routeIs('admin.hoteis*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i>Hotéis
            </a>
            <a href="{{ route('admin.utilizadores.index') }}"
               class="nav-link {{ request()->routeIs('admin.utilizadores*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>Utilizadores
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                <i class="bi bi-star me-2"></i>Avaliações
            </a>
        @else
            {{-- Menu Gestor --}}
            <div class="nav-section">Principal</div>
            <a href="{{ route('manager.dashboard') }}"
               class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>

            <div class="nav-section">O Meu Hotel</div>
            <a href="{{ route('manager.hotel.index') }}"
               class="nav-link {{ request()->routeIs('manager.hotel*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i>Informações
            </a>
            <a href="{{ route('manager.quartos.index') }}"
               class="nav-link {{ request()->routeIs('manager.quartos*') ? 'active' : '' }}">
                <i class="bi bi-door-open me-2"></i>Quartos
            </a>
        @endif

        <div class="nav-section">Conta</div>
        <a href="{{ route('home') }}" class="nav-link">
            <i class="bi bi-globe me-2"></i>Ver site
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>Sair
            </button>
        </form>
    </nav>
</aside>

{{-- ── MAIN ── --}}
<div class="main-content">
    {{-- Topbar --}}
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-lg-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="small text-muted d-none d-md-inline">{{ auth()->user()->name }}</span>
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                 style="width:34px;height:34px;font-size:.85rem">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    <div class="flash-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible shadow-sm fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible shadow-sm fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible shadow-sm fade show">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Conteúdo da página --}}
    <div class="p-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Auto-fechar alertas
    setTimeout(() => {
        document.querySelectorAll('.flash-container .alert').forEach(el => {
            bootstrap.Alert.getOrCreateInstance(el).close();
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>