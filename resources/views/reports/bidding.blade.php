@use('App\Support\Money')

@php
    $completed = $bidding->stages->filter->isCompleted();
    $total = $bidding->stages->count() + $pendingSteps->count();
@endphp

<x-sigil.report :title="'Processo licitatório '.$bidding->code" :city-hall="$cityHall" :subtitle="$bidding->mode->name">
    <table class="summary" style="margin-bottom: 12px">
        <tr>
            <td class="muted" style="width: 95px">Objeto</td>
            <td class="bold">{{ $bidding->subject }}</td>
        </tr>
        <tr>
            <td class="muted">Tipo</td>
            <td>{{ $bidding->type->label() }}</td>
        </tr>
        <tr>
            <td class="muted">Valor estimado</td>
            <td>{{ Money::display($bidding->estimated_value) }}</td>
        </tr>
        <tr>
            <td class="muted">Situação</td>
            <td>
                @if ($bidding->isCompleted())
                    <span class="badge badge-success">Concluída</span>
                    em {{ $bidding->completed_at->format('d/m/Y') }}
                @else
                    <span class="badge badge-primary">Em andamento</span>
                    {{ $completed->count() }} de {{ $total }} etapas concluídas
                @endif
            </td>
        </tr>
    </table>

    <div class="section">Etapas do processo</div>
    <table>
        <thead>
            <tr>
                <th style="width: 18px">#</th>
                <th>Etapa</th>
                <th style="width: 55px">Início</th>
                <th style="width: 62px">Conclusão</th>
                <th class="center" style="width: 30px">Dias</th>
                <th style="width: 120px">Responsável</th>
                <th style="width: 34px">Página</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bidding->stages as $stage)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="bold">
                        {{ $stage->step->name }}
                        @if ($stage->notes)
                            <div class="muted small" style="font-weight: normal">{{ $stage->notes }}</div>
                        @endif
                    </td>
                    <td class="nowrap">{{ $stage->started_at->format('d/m/Y') }}</td>
                    <td class="nowrap">
                        @if ($stage->isCompleted())
                            {{ $stage->completed_at->format('d/m/Y') }}
                        @else
                            <span class="badge badge-primary">Em andamento</span>
                        @endif
                    </td>
                    <td class="center">{{ $stage->days() }}</td>
                    <td>
                        {{ $stage->professional->name }}
                        <div class="muted small">{{ $stage->secretary->name }}</div>
                    </td>
                    <td>{{ $stage->page_number ?? '—' }}</td>
                </tr>
            @endforeach

            @foreach ($pendingSteps as $step)
                <tr>
                    <td class="muted">{{ $bidding->stages->count() + $loop->iteration }}</td>
                    <td class="muted">{{ $step->name }}</td>
                    <td colspan="5"><span class="badge badge-secondary">Pendente</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-sigil.report>
