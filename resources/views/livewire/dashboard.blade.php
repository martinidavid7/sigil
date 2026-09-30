@use('App\Support\Money')

<div>
    <x-sigil.page-header title="Painel" :subtitle="$cityHall?->name ?? 'Bem-vindo(a) ao SIGIL'" />

    @unless ($cityHall)
        <div class="card border-left-warning shadow mb-4">
            <div class="card-body d-sm-flex align-items-center justify-content-between">
                <div>
                    <div class="h5 font-weight-bold text-gray-800 mb-1">Comece pela prefeitura</div>
                    <p class="mb-0 text-muted">Os demais cadastros são liberados depois que o órgão for configurado.</p>
                </div>
                <a href="{{ route('city-hall') }}" class="btn btn-warning mt-3 mt-sm-0">
                    <i class="fas fa-landmark mr-1"></i> Cadastrar prefeitura
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($stats as $stat)
                <div class="col-md-6 col-xl-3 mb-4">
                    <a href="{{ route($stat['route']) }}" class="text-decoration-none">
                        <div class="card border-left-{{ $stat['color'] }} shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-{{ $stat['color'] }} text-uppercase mb-1">
                                            {{ $stat['label'] }}
                                        </div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stat['value'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas {{ $stat['icon'] }} fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xl-8 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Qual modalidade usar?</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="pl-3">Modalidade</th>
                                    <th>Compras e serviços</th>
                                    <th>Obras e engenharia</th>
                                    <th class="text-right pr-3">Prazo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($modes as $mode)
                                    <tr wire:key="dash-mode-{{ $mode->id }}">
                                        <td class="pl-3 font-weight-bold text-gray-800">{{ $mode->name }}</td>
                                        <td>{{ Money::range($mode->purchase_services_minimum_value, $mode->purchase_services_maximum_value) }}</td>
                                        <td>{{ Money::range($mode->construction_engineering_minimum_value, $mode->construction_engineering_maximum_value) }}</td>
                                        <td class="text-right text-nowrap pr-3">{{ $mode->deadline ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Nenhuma modalidade ativa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Fluxo do processo</h6>
                    </div>
                    <ol class="list-group list-group-flush">
                        @forelse ($steps as $step)
                            <li class="list-group-item py-2 d-flex align-items-center" wire:key="dash-step-{{ $step->id }}">
                                <span class="badge badge-pill badge-primary mr-2">{{ $loop->iteration }}</span>
                                {{ $step->name }}
                                @if ($total = $inProgressByStep[$step->id] ?? 0)
                                    <a href="{{ route('biddings.index') }}" class="badge badge-warning ml-auto"
                                        title="Licitações nesta etapa">{{ $total }}</a>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Nenhuma etapa ativa.</li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </div>
    @endunless
</div>
