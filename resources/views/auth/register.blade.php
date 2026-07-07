<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — HotelCompare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <style>
        :root {
            --hc-primary: #1a5276;
            --hc-accent:  #f39c12;
            --hc-dark:    #0d1b2a;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d1b2a 0%, #1a5276 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
        }

        .auth-header {
            background: linear-gradient(135deg, #0d1b2a, #1a5276);
            padding: 2rem;
            text-align: center;
        }

        .auth-header .brand span { color: var(--hc-accent); }

        .auth-body { padding: 2rem; }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: .75rem 1rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--hc-primary);
            box-shadow: 0 0 0 3px rgba(26,82,118,.15);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            background: #f8fafc;
            color: #94a3b8;
        }

        .input-group .form-control,
        .input-group .form-select { border-radius: 0 10px 10px 0; }

        .btn-register {
            background: linear-gradient(135deg, var(--hc-accent), #d68910);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .8rem;
            font-weight: 600;
            letter-spacing: .3px;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(243,156,18,.35);
            color: #fff;
        }

        .role-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
        }

        .role-card:hover { border-color: var(--hc-primary); background: #f0f7ff; }
        .role-card.selected { border-color: var(--hc-primary); background: #f0f7ff; }
        .role-card input { display: none; }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #94a3b8;
            font-size: .85rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
    </style>
</head>
<body>

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <a href="{{ route('home') }}" class="text-decoration-none">
            <div class="brand fw-bold fs-3 text-white mb-1">
                <i class="bi bi-building me-1"></i>Hotel<span>Compare</span>
            </div>
        </a>
        <p class="text-white-50 small mb-0">Crie a sua conta gratuitamente</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">
        <h5 class="fw-bold mb-1">Criar conta</h5>
        <p class="text-muted small mb-4">Preencha os dados abaixo para começar</p>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible rounded-3 py-2 mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Tipo de conta --}}
            <div class="mb-4">
                <label class="form-label small fw-semibold mb-2">Tipo de conta</label>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="role-card d-block selected" id="card-guest" onclick="selectRole('guest')">
                            <input type="radio" name="role" value="guest" checked>
                            <i class="bi bi-person-circle fs-2 text-primary d-block mb-1"></i>
                            <div class="fw-semibold small">Hóspede</div>
                            <div class="text-muted" style="font-size:.75rem;">Pesquisar e avaliar hotéis</div>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="role-card d-block" id="card-manager" onclick="selectRole('hotel_manager')">
                            <input type="radio" name="role" value="hotel_manager">
                            <i class="bi bi-building fs-2 d-block mb-1" style="color:var(--hc-accent);"></i>
                            <div class="fw-semibold small">Gestor de Hotel</div>
                            <div class="text-muted" style="font-size:.75rem;">Gerir o meu hotel</div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Nome --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold">Nome completo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="O seu nome completo"
                           required autofocus>
                </div>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="exemplo@email.com"
                           required>
                </div>
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold">Palavra-passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Mínimo 8 caracteres"
                           required id="passwordInput">
                    <button type="button" class="btn btn-outline-secondary border-start-0"
                            style="border-radius:0 10px 10px 0;border:1.5px solid #e2e8f0;border-left:none;"
                            onclick="togglePassword('passwordInput', 'eyeIcon1')">
                        <i class="bi bi-eye" id="eyeIcon1"></i>
                    </button>
                </div>
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- Confirmar Password --}}
            <div class="mb-4">
                <label class="form-label small fw-semibold">Confirmar palavra-passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Repita a palavra-passe"
                           required id="passwordConfirm">
                    <button type="button" class="btn btn-outline-secondary border-start-0"
                            style="border-radius:0 10px 10px 0;border:1.5px solid #e2e8f0;border-left:none;"
                            onclick="togglePassword('passwordConfirm', 'eyeIcon2')">
                        <i class="bi bi-eye" id="eyeIcon2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-register w-100 mb-3">
                <i class="bi bi-person-plus me-2"></i>Criar conta
            </button>
        </form>

        <div class="divider mb-3">ou</div>

        <div class="text-center small text-muted">
            Já tem conta?
            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none"
               style="color:var(--hc-primary);">
                Iniciar sessão
            </a>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="small text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Voltar ao site
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function selectRole(role) {
        document.getElementById('card-guest').classList.remove('selected');
        document.getElementById('card-manager').classList.remove('selected');
        document.getElementById('card-' + (role === 'guest' ? 'guest' : 'manager')).classList.add('selected');
    }

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
</body>
</html>