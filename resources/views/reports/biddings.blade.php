@use('App\Support\Money')

<x-sigil.report :title="$report->title()" :city-hall="$cityHall" :filters="$report->appliedFilters()"
    :subtitle="trans_choice(':count processo|:count processos', $biddings->count())">
    <table>
        <thead>
            <tr>
                <th style="width: 55px">Processo</th>
                <th>Objeto</th>
                <th style="width: 90px">Modalidade</th>
                <th class="right" style="width: 80px">Valor estimado</th>
                <th style="width: 58px">Abertura</th>
                <th style="width: 150px">Etapa atual</th>
                <th style="width: 140px">Responsável</th>
                <th class="center" style="width: 45px">Etapas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($biddings as $bidding)
                @php($stage = $bidding->currentStage)
                <tr>
                    <td class="bold nowrap">{{ $bidding->code }}</td>
                    <td>
                        {{ $bidding->subject }}
                        <div class="muted small">{{ $bidding->type->label() }}</div>
                    </td>
                    <td>{{ $bidding->mode->name }}</td>
                    <td class="right nowrap">{{ Money::display($bidding->estimated_value) }}</td>
                    <td class="nowrap">{{ $bidding->stages->first()?->started_at->format('d/m/Y') }}</td>
                    <td>
                        @if ($stage)
                            {{ $stage->step->name }}
                            <div class="muted small">
                                desde {{ $stage->started_at->format('d/m/Y') }}
                                ({{ trans_choice(':count dia|:count dias', $stage->days()) }})
                            </div>
                        @else
                            <span class="badge badge-success">Concluída</span>
                            <div class="muted small">em {{ $bidding->completed_at->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td>
                        @if ($stage)
                            {{ $stage->professional->name }}
                            <div class="muted small">{{ $stage->secretary->name }}</div>
                        @else
                            <span class="muted">—</span>
                        @endif
                    </td>
                    <td class="center">{{ $bidding->stages->filter->isCompleted()->count() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty">Nenhum processo encontrado com estes filtros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($biddings->isNotEmpty())
        <table class="summary" style="width: 280px; margin: 10px 0 0 auto">
            <tr>
                <td>Processos em andamento</td>
                <td class="right bold">{{ $biddings->reject->isCompleted()->count() }}</td>
            </tr>
            <tr>
                <td>Processos concluídos</td>
                <td class="right bold">{{ $biddings->filter->isCompleted()->count() }}</td>
            </tr>
            <tr>
                <td>Valor estimado total</td>
                <td class="right bold">{{ Money::display($biddings->sum('estimated_value')) }}</td>
            </tr>
        </table>
    @endif
</x-sigil.report>
