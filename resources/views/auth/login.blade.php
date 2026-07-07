<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar — HotelCompare</title>
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
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }

        .auth-header {
            background: linear-gradient(135deg, #0d1b2a, #1a5276);
            padding: 2rem;
            text-align: center;
        }

        .auth-header .brand span { color: var(--hc-accent); }

        .auth-body { padding: 2rem; }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: .75rem 1rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
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

        .input-group .form-control { border-radius: 0 10px 10px 0; }

        .btn-login {
            background: linear-gradient(135deg, #1a5276, #154360);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .8rem;
            font-weight: 600;
            letter-spacing: .3px;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(26,82,118,.35);
            color: #fff;
        }

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
        <p class="text-white-50 small mb-0">A plataforma de hotéis em Benguela</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">
        <h5 class="fw-bold mb-1">Bem-vindo de volta</h5>
        <p class="text-muted small mb-4">Inicie sessão na sua conta</p>

        {{-- Erros de validação --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible rounded-3 py-2 mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Session status (ex: logout confirmado) --}}
        @if(session('status'))
            <div class="alert alert-success rounded-3 py-2 mb-3 small">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="exemplo@email.com"
                           required autofocus>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label small fw-semibold mb-0">Palavra-passe</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="small text-decoration-none" style="color:var(--hc-primary);">
                            Esqueceu a palavra-passe?
                        </a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="A sua palavra-passe"
                           required id="passwordInput">
                    <button type="button" class="btn btn-outline-secondary border-start-0"
                            style="border-radius:0 10px 10px 0;border:1.5px solid #e2e8f0;border-left:none;"
                            onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            {{-- Lembrar --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">
                        Manter sessão iniciada
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
            </button>
        </form>

        <div class="divider mb-3">ou</div>

        <div class="text-center small text-muted">
            Ainda não tem conta?
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none"
               style="color:var(--hc-primary);">
                Registar agora
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
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
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