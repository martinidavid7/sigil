<div>
    <x-sigil.page-header title="Etapas do processo"
        subtitle="Sequência padrão de etapas pela qual uma licitação passa.">
        <x-slot:actions>
            <button type="button" class="btn btn-primary shadow-sm" wire:click="create">
                <i class="fas fa-plus fa-sm mr-1"></i> Nova etapa
            </button>
        </x-slot:actions>
    </x-sigil.page-header>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-pills card-header-pills">
                <li class="nav-item">
                    <a href="#" @class(['nav-link', 'active' => $status === 'ativas'])
                        wire:click.prevent="$set('status', 'ativas')">Ativas</a>
                </li>
                <li class="nav-item">
                    <a href="#" @class(['nav-link', 'active' => $status === 'inativas'])
                        wire:click.prevent="$set('status', 'inativas')">
                        Inativas
                        @if ($disabledCount)
                            <span class="badge badge-secondary ml-1">{{ $disabledCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 70px">Ordem</th>
                        <th>Etapa</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($steps as $step)
                        <tr wire:key="step-{{ $step->id }}">
                            <td class="align-middle text-center">
                                <span class="badge badge-pill badge-primary">{{ $loop->iteration }}</span>
                            </td>
                            <td class="align-middle font-weight-bold text-gray-800">{{ $step->name }}</td>
                            <td class="align-middle text-right text-nowrap">
                                <div class="btn-group btn-group-sm mr-2" role="group" aria-label="Reordenar">
                                    <button type="button" class="btn btn-outline-secondary" title="Mover para cima"
                                        wire:click="moveUp({{ $step->id }})" @disabled($loop->first)>
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" title="Mover para baixo"
                                        wire:click="moveDown({{ $step->id }})" @disabled($loop->last)>
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="edit({{ $step->id }})">
                                    <i class="fas fa-pen fa-sm"></i> Editar
                                </button>
                                <button type="button" @class([
                                    'btn btn-sm',
                                    'btn-outline-warning' => $step->enabled,
                                    'btn-outline-success' => ! $step->enabled,
                                ]) wire:click="toggle({{ $step->id }})">
                                    @if ($step->enabled)
                                        <i class="fas fa-ban fa-sm"></i> Desativar
                                    @else
                                        <i class="fas fa-check fa-sm"></i> Reativar
                                    @endif
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                {{ $status === 'inativas' ? 'Nenhuma etapa inativa.' : 'Nenhuma etapa ativa.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-sigil.modal :show="$showModal" size="md" :title="$form->step?->exists ? 'Editar etapa' : 'Nova etapa'">
        <form wire:submit="save">
            <div class="modal-body">
                <x-sigil.input label="Etapa" wire:model="form.name" placeholder="Ex.: Análise do Jurídico" />
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="step-enabled" wire:model="form.enabled">
                    <label class="custom-control-label" for="step-enabled">Etapa ativa</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <i class="fas fa-save mr-1"></i> Salvar
                </button>
            </div>
        </form>
    </x-sigil.modal>
</div>
