<x-sigil.report :title="$report->title()" :city-hall="$cityHall" :filters="$report->appliedFilters()"
    :subtitle="trans_choice(':count etapa|:count etapas', $stages->count())">
    <table>
        <thead>
            <tr>
                <th style="width: 55px">Processo</th>
                <th>Etapa</th>
                <th style="width: 58px">Início</th>
                <th style="width: 70px">Conclusão</th>
                <th class="center" style="width: 38px">Dias</th>
                <th style="width: 150px">Responsável</th>
                <th style="width: 40px">Página</th>
                <th style="width: 190px">Observações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stages as $stage)
                <tr>
                    <td class="bold nowrap">{{ $stage->bidding->code }}</td>
                    <td>
                        {{ $stage->step->name }}
                        <div class="muted small">{{ Str::limit($stage->bidding->subject, 70) }}</div>
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
                    <td class="small">{{ $stage->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty">Nenhuma etapa encontrada com estes filtros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($averages->isNotEmpty())
        <div class="section">Tempo médio das etapas concluídas</div>
        <table style="width: 420px">
            <thead>
                <tr>
                    <th>Etapa</th>
                    <th class="center" style="width: 70px">Concluídas</th>
                    <th class="center" style="width: 90px">Média (dias)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($averages as $row)
                    <tr>
                        <td>{{ $row['step']->name }}</td>
                        <td class="center">{{ $row['count'] }}</td>
                        <td class="center">{{ number_format($row['average'], 1, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-sigil.report>
