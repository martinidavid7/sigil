@use('App\Support\Money')

<div>
    <x-sigil.page-header title="Licitações" subtitle="Processos licitatórios e a etapa em que cada um se encontra.">
        <x-slot:actions>
            <button type="button" class="btn btn-primary shadow-sm" wire:click="create">
                <i class="fas fa-plus fa-sm mr-1"></i> Nova licitação
            </button>
        </x-slot:actions>
    </x-sigil.page-header>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-md-flex align-items-center justify-content-between">
            <ul class="nav nav-pills card-header-pills mb-2 mb-md-0">
                @foreach (['andamento' => 'Em andamento', 'concluidas' => 'Concluídas', 'todas' => 'Todas'] as $value => $label)
                    <li class="nav-item">
                        <a href="#" @class(['nav-link', 'active' => $status === $value])
                            wire:click.prevent="$set('status', '{{ $value }}')">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="input-group" style="max-width: 360px">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-gray-400"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Buscar por número ou objeto"
                    aria-label="Buscar" wire:model.live.debounce.300ms="search">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Processo</th>
                        <th>Objeto</th>
                        <th>Modalidade</th>
                        <th>Etapa atual</th>
                        <th>Responsável</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($biddings as $bidding)
                        @php($stage = $bidding->currentStage)
                        <tr wire:key="bidding-{{ $bidding->id }}">
                            <td class="align-middle font-weight-bold text-gray-800 text-nowrap">{{ $bidding->code }}</td>
                            <td class="align-middle">
                                {{ Str::limit($bidding->subject, 80) }}
                                @if ($bidding->estimated_value !== null)
                                    <div class="small text-muted">{{ Money::display($bidding->estimated_value) }}</div>
                                @endif
                            </td>
                            <td class="align-middle text-nowrap">{{ $bidding->mode->name }}</td>
                            <td class="align-middle">
                                @if ($stage)
                                    <span class="font-weight-bold">{{ $stage->step->name }}</span>
                                    <div class="small text-muted">
                                        desde {{ $stage->started_at->format('d/m/Y') }}
                                        ({{ trans_choice(':count dia|:count dias', $stage->days()) }})
                                    </div>
                                @else
                                    <span class="badge badge-success">Concluída em {{ $bidding->completed_at->format('d/m/Y') }}</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if ($stage)
                                    {{ $stage->professional->name }}
                                    <div class="small text-muted">{{ $stage->secretary->name }}</div>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="align-middle text-right text-nowrap">
                                <a href="{{ route('biddings.show', $bidding) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-stream fa-sm"></i> Acompanhar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                @if ($search)
                                    Nenhuma licitação encontrada para "{{ $search }}".
                                @else
                                    Nenhuma licitação nesta situação.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($biddings->hasPages())
            <div class="card-footer bg-white">{{ $biddings->links() }}</div>
        @endif
    </div>

    <x-sigil.modal :show="$showModal" title="Nova licitação">
        <form wire:submit="save">
            <div class="modal-body">
                @include('livewire.biddings.partials.bidding-fields')

                <h6 class="font-weight-bold text-primary mt-2">
                    Primeira etapa: {{ $this->firstStep?->name }}
                </h6>
                <div class="form-row">
                    <x-sigil.input class="col-md-4" type="date" label="Data de início" wire:model="form.started_at"
                        max="{{ today()->toDateString() }}" />
                    <x-sigil.select class="col-md-8" label="Secretaria responsável" wire:model.live="form.secretary_id"
                        :options="$this->secretaryOptions" />
                </div>
                <x-sigil.select label="Profissional responsável" wire:model="form.professional_id"
                    :options="$this->professionalOptions($form->secretary_id)"
                    :placeholder="$form->secretary_id ? 'Selecione...' : 'Escolha a secretaria primeiro'"
                    :hint="$form->secretary_id && $this->professionalOptions($form->secretary_id)->isEmpty() ? 'Nenhum profissional cadastrado nesta secretaria.' : null" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <i class="fas fa-play mr-1"></i> Abrir licitação
                </button>
            </div>
        </form>
    </x-sigil.modal>
</div>
