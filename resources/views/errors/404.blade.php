<div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
</div>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada — HotelCompare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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
            text-align: center;
            padding: 2rem;
        }

        .error-card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 24px;
            padding: 3rem 2rem;
            max-width: 520px;
            width: 100%;
            backdrop-filter: blur(10px);
        }

        .error-number {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #f39c12, #e67e22);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-icon {
            font-size: 4rem;
            color: rgba(255,255,255,.3);
        }

        .btn-home {
            background: linear-gradient(135deg, #f39c12, #d68910);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: .8rem 2rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform .2s, box-shadow .2s;
            display: inline-block;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(243,156,18,.4);
            color: #fff;
        }

        .btn-back {
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: .9rem;
            transition: color .2s;
        }

        .btn-back:hover { color: #fff; }
    </style>
</head>
<body>

<div class="error-card">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="text-decoration-none d-inline-block mb-4">
        <span class="fw-bold fs-4 text-white">
            <i class="bi bi-building me-1"></i>Hotel<span style="color:#f39c12;">Compare</span>
        </span>
    </a>

    {{-- Ícone --}}
    <div class="error-icon mb-2">
        <i class="bi bi-building-x"></i>
    </div>

    {{-- Número --}}
    <div class="error-number">404</div>

    {{-- Mensagem --}}
    <h3 class="text-white fw-bold mt-2 mb-2">Página não encontrada</h3>
    <p class="mb-4" style="color:rgba(255,255,255,.6);">
        O hotel que procura não existe ou foi removido.<br>
        Verifique o endereço ou volte à página inicial.
    </p>

    {{-- Acções --}}
    <div class="d-flex flex-column align-items-center gap-3">
        <a href="{{ route('home') }}" class="btn-home">
            <i class="bi bi-house me-2"></i>Voltar ao início
        </a>
        <a href="{{ route('hotels.index') }}" class="btn-back">
            <i class="bi bi-building me-1"></i>Ver todos os hotéis
        </a>
        <a href="javascript:history.back()" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i>Página anterior
        </a>
    </div>
</div>

</body>
</html>