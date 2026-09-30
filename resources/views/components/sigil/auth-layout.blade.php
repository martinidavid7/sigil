{{--
    Layout das telas de autenticação, no mesmo visual (SB Admin 2) do painel:
    painel da marca à esquerda (telas grandes) e o formulário à direita.
--}}
@props(['title'])

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SIGIL - Sistema de Gestão de Licitações">

    <title>{{ $title }} · SIGIL</title>

    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .auth-page {
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 20%, rgba(255, 255, 255, .08) 0, transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(255, 255, 255, .06) 0, transparent 45%),
                linear-gradient(135deg, #4e73df 0%, #224abe 60%, #1a3690 100%);
        }

        .auth-card {
            border-radius: 1rem;
            max-width: 980px;
        }

        .auth-brand {
            background:
                linear-gradient(160deg, rgba(26, 54, 144, .92), rgba(34, 74, 190, .92)),
                repeating-linear-gradient(45deg, rgba(255, 255, 255, .04) 0 2px, transparent 2px 14px);
            color: #fff;
        }

        .auth-brand .feature-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: .6rem;
            background: rgba(255, 255, 255, .15);
            flex-shrink: 0;
        }

        .auth-form .input-group-text {
            background: #f8f9fc;
            color: #b7b9cc;
            min-width: 2.75rem;
            justify-content: center;
        }

        .auth-form .form-control {
            height: calc(1.5em + 1.5rem + 2px);
        }

        .auth-form .form-control:focus {
            box-shadow: none;
        }

        .auth-form .input-group:focus-within .input-group-text,
        .auth-form .input-group:focus-within .form-control,
        .auth-form .input-group:focus-within .btn {
            border-color: #bac8f3;
        }

        .auth-form .input-group:focus-within {
            box-shadow: 0 0 0 .2rem rgba(78, 115, 223, .25);
            border-radius: .35rem;
        }
    </style>

    @livewireStyles
</head>

<body class="auth-page d-flex align-items-center py-4">
    <div class="container w-100">
        <div class="card auth-card o-hidden border-0 shadow-lg mx-auto">
            <div class="row no-gutters">
                <div class="col-lg-6 d-none d-lg-flex auth-brand">
                    <div class="d-flex flex-column justify-content-between p-5 w-100">
                        <div>
                            <img src="{{ asset('img/logo.png') }}" alt="SIGIL" style="height: 64px">
                            <h2 class="h4 font-weight-bold mt-4 mb-2">Sistema de Gestão de Licitações</h2>
                            <p class="mb-0 text-white-50">
                                Organize os processos licitatórios da prefeitura do protocolo à homologação.
                            </p>
                        </div>

                        <ul class="list-unstyled my-5">
                            @foreach ([
                                ['fa-stream', 'Processos etapa por etapa', 'Cada licitação segue o fluxo cadastrado, com datas e páginas registradas.'],
                                ['fa-user-check', 'Responsáveis sempre claros', 'Saiba com qual secretaria e profissional o processo está, e desde quando.'],
                                ['fa-balance-scale', 'Modalidade certa', 'Sugestão de modalidade pela faixa de valor do objeto.'],
                            ] as [$icon, $heading, $text])
                                <li class="d-flex mb-4">
                                    <span class="feature-icon d-flex align-items-center justify-content-center mr-3">
                                        <i class="fas {{ $icon }}"></i>
                                    </span>
                                    <div>
                                        <div class="font-weight-bold">{{ $heading }}</div>
                                        <div class="small text-white-50">{{ $text }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="small text-white-50">&copy; {{ date('Y') }} Martini Software</div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 p-sm-5 auth-form">
                        <div class="text-center d-lg-none mb-4">
                            <img src="{{ asset('img/logofundotransp.png') }}" alt="SIGIL" style="height: 56px">
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
