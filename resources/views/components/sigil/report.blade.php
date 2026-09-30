{{--
    Layout dos relatórios em PDF (dompdf): cabeçalho com a prefeitura,
    título, filtros aplicados e rodapé com emissão e numeração de páginas.
--}}
@props(['title', 'cityHall' => null, 'subtitle' => null, 'filters' => []])

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 105px 32px 52px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.5pt;
            color: #3a3b45;
        }

        header {
            position: fixed;
            top: -82px;
            left: 0;
            right: 0;
            height: 64px;
            border-bottom: 2px solid #224abe;
        }

        header .logo {
            position: absolute;
            left: 0;
            top: 4px;
            height: 46px;
        }

        header .org {
            position: absolute;
            left: 120px;
            right: 0;
            top: 6px;
        }

        header .org-name {
            font-size: 11pt;
            font-weight: bold;
            color: #224abe;
        }

        header .org-details {
            font-size: 7.5pt;
            color: #858796;
            margin-top: 3px;
        }

        footer {
            position: fixed;
            bottom: -34px;
            left: 0;
            right: 0;
            height: 20px;
            border-top: 1px solid #e3e6f0;
            padding-top: 5px;
            font-size: 7pt;
            color: #858796;
        }

        h1 {
            font-size: 13pt;
            margin: 0 0 2px;
            color: #2e2f37;
        }

        .subtitle {
            color: #858796;
            margin-bottom: 8px;
        }

        .filters {
            background: #f8f9fc;
            border-left: 3px solid #4e73df;
            padding: 5px 8px;
            margin-bottom: 12px;
            font-size: 7.5pt;
        }

        .filters span {
            margin-right: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #224abe;
            color: #fff;
            font-size: 7.5pt;
            text-align: left;
            padding: 5px 6px;
        }

        td {
            padding: 5px 6px;
            border-bottom: 1px solid #e3e6f0;
            vertical-align: top;
        }

        tr {
            page-break-inside: avoid;
        }

        tbody tr:nth-child(even) td {
            background: #f8f9fc;
        }

        .muted {
            color: #858796;
        }

        .small {
            font-size: 7.5pt;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }

        .bold {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            color: #fff;
        }

        .badge-primary {
            background: #4e73df;
        }

        .badge-success {
            background: #1cc88a;
        }

        .badge-secondary {
            background: #b7b9cc;
        }

        .section {
            font-size: 10pt;
            font-weight: bold;
            color: #224abe;
            margin: 16px 0 6px;
        }

        .summary td {
            border: none;
            background: none !important;
            padding: 2px 0;
        }

        .empty {
            text-align: center;
            color: #858796;
            padding: 24px;
        }
    </style>
</head>

<body>
    <header>
        <img class="logo" src="{{ public_path('img/logofundotransp.png') }}" alt="SIGIL">
        <div class="org">
            <div class="org-name">{{ $cityHall?->name ?? 'SIGIL' }}</div>
            @if ($cityHall)
                <div class="org-details">
                    {{ collect([
                        $cityHall->cnpj ? "CNPJ {$cityHall->cnpj}" : null,
                        "{$cityHall->address}, {$cityHall->number} - {$cityHall->neighborhood}",
                        $cityHall->city,
                        $cityHall->phone,
                    ])->filter()->join(' · ') }}
                </div>
            @endif
            <div class="org-details">Sistema de Gestão de Licitações</div>
        </div>
    </header>

    <footer>
        {{-- A numeração de páginas é escrita pelo ReportController, à direita. --}}
        Emitido em {{ now()->format('d/m/Y \à\s H:i') }}@auth por {{ auth()->user()->name }}@endauth
    </footer>

    <h1>{{ $title }}</h1>
    @if ($subtitle)
        <div class="subtitle">{{ $subtitle }}</div>
    @endif

    @if ($filters)
        <div class="filters">
            @foreach ($filters as $label => $value)
                <span><strong>{{ $label }}:</strong> {{ $value }}</span>
            @endforeach
        </div>
    @endif

    {{ $slot }}
</body>

</html>
