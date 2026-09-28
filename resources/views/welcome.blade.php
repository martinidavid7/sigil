<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SIGIL - Sistema de Gestão de Licitações para prefeituras">

    <title>SIGIL · Sistema de Gestão de Licitações</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>

<body>
    <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="{{ asset('assets/mp4/bg.mp4') }}" type="video/mp4" />
    </video>

    <div class="masthead">
        <div class="masthead-content text-white">
            <div class="container-fluid px-4 px-lg-0">
                <h1 class="fst-italic lh-1 mb-4">SIGIL</h1>
                <p class="mb-4">Sistema de Gestão de Licitações</p>
                <p class="mb-5 small">
                    Cadastro de secretarias, modalidades de licitação com suas faixas de valor
                    e o fluxo de etapas de cada processo, em um só lugar.
                </p>

                @auth
                    <a class="btn btn-light btn-lg" href="{{ route('dashboard') }}">Acessar o painel</a>
                @else
                    <a class="btn btn-light btn-lg" href="{{ route('login') }}">Entrar</a>
                    @if (Route::has('register'))
                        <a class="btn btn-outline-light btn-lg ms-2" href="{{ route('register') }}">Criar conta</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
