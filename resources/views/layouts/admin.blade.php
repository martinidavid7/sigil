<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SIGIL - Sistema de Gestão de Licitações">

    <title>{{ isset($title) ? "{$title} · SIGIL" : 'SIGIL' }}</title>

    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
        integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @livewireStyles
</head>

<body id="page-top">

    <div id="wrapper">

        {{-- Sidebar --}}
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SIGIL</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Painel</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">Cadastros</div>

            <li class="nav-item {{ request()->routeIs('city-hall') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('city-hall') }}">
                    <i class="fas fa-fw fa-landmark"></i>
                    <span>Prefeitura</span>
                </a>
            </li>

            @if ($cityHallConfigured)
                <li class="nav-item {{ request()->routeIs('secretaries.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('secretaries.index') }}">
                        <i class="fas fa-fw fa-building"></i>
                        <span>Secretarias</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('professionals.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('professionals.index') }}">
                        <i class="fas fa-fw fa-user-tie"></i>
                        <span>Profissionais</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

                <div class="sidebar-heading">Licitações</div>

                <li class="nav-item {{ request()->routeIs('biddings.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('biddings.index') }}">
                        <i class="fas fa-fw fa-folder-open"></i>
                        <span>Processos</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('bidding-modes.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bidding-modes.index') }}">
                        <i class="fas fa-fw fa-balance-scale"></i>
                        <span>Modalidades</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('bidding-steps.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bidding-steps.index') }}">
                        <i class="fas fa-fw fa-list-ol"></i>
                        <span>Etapas</span>
                    </a>
                </li>
            @endif

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle" aria-label="Recolher menu"></button>
            </div>

            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="{{ asset('img/logo.png') }}" alt="SIGIL">
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                {{-- Topbar --}}
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"
                        aria-label="Abrir menu">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="{{ Auth::user()->profile_photo_url }}"
                                    alt="{{ Auth::user()->name }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Meu perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Sair
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>SIGIL &middot; Sistema de Gestão de Licitações &copy; {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top" aria-label="Voltar ao topo">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Logout --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Deseja mesmo sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Sua sessão será encerrada.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/js/sb-admin-2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"
        integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @livewireScripts

    <script>
        toastr.options = { positionClass: 'toast-top-right', progressBar: true, timeOut: 4000 };

        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', ({ message, type = 'success' }) => toastr[type](message));
        });

        @session('notify')
            toastr[@js($value['type'])](@js($value['message']));
        @endsession
    </script>
</body>

</html>
