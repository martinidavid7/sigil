@use('App\Support\Money')

<div>
    <x-sigil.page-header title="Modalidades de licitação"
        subtitle="Faixas de valor e prazos de publicidade de cada modalidade.">
        <x-slot:actions>
            <button type="button" class="btn btn-primary shadow-sm" wire:click="create">
                <i class="fas fa-plus fa-sm mr-1"></i> Nova modalidade
            </button>
        </x-slot:actions>
    </x-sigil.page-header>

    <div class="card shadow mb-4">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Modalidade</th>
                        <th>Prazo</th>
                        <th>Compras e serviços</th>
                        <th>Obras e engenharia</th>
                        <th class="text-center">Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modes as $mode)
                        <tr wire:key="mode-{{ $mode->id }}">
                            <td class="align-middle font-weight-bold text-gray-800 text-nowrap">{{ $mode->name }}</td>
                            <td class="align-middle text-nowrap">{{ $mode->deadline ?? '—' }}</td>
                            <td class="align-middle">{{ Money::range($mode->purchase_services_minimum_value, $mode->purchase_services_maximum_value) }}</td>
                            <td class="align-middle">{{ Money::range($mode->construction_engineering_minimum_value, $mode->construction_engineering_maximum_value) }}</td>
                            <td class="align-middle text-center"><x-sigil.status-badge :enabled="$mode->enabled" /></td>
                            <td class="align-middle text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="edit({{ $mode->id }})" title="Editar">
                                    <i class="fas fa-pen fa-sm"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    wire:click="toggle({{ $mode->id }})"
                                    title="{{ $mode->enabled ? 'Desativar' : 'Ativar' }}">
                                    <i @class(['fas fa-sm', 'fa-toggle-on text-success' => $mode->enabled, 'fa-toggle-off' => ! $mode->enabled])></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    wire:click="delete({{ $mode->id }})"
                                    wire:confirm="Excluir a modalidade {{ $mode->name }}?"
                                    title="Excluir">
                                    <i class="fas fa-trash fa-sm"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Nenhuma modalidade cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-sigil.modal :show="$showModal" :title="$form->mode?->exists ? 'Editar modalidade' : 'Nova modalidade'">
        <form wire:submit="save">
            <div class="modal-body">
                <div class="form-row">
                    <x-sigil.input class="col-md-7" label="Modalidade" wire:model="form.name" />
                    <x-sigil.input class="col-md-5" label="Prazo de publicidade" wire:model="form.deadline"
                        placeholder="Ex.: 8 dias úteis" />
                </div>

                @foreach ([
                    'purchase_services' => 'Compras e serviços',
                    'construction_engineering' => 'Obras e engenharia',
                ] as $range => $label)
                    <h6 class="font-weight-bold text-primary mt-2">{{ $label }}</h6>
                    <div class="form-row">
                        @foreach (['minimum' => 'Valor mínimo (R$)', 'maximum' => 'Valor máximo (R$)'] as $bound => $boundLabel)
                            <x-sigil.input class="col-md-6" :label="$boundLabel" inputmode="decimal"
                                wire:model="form.{{ $range }}_{{ $bound }}_value"
                                x-mask:dynamic="$money($input, ',', '.')" placeholder="0,00"
                                :hint="$bound === 'maximum' ? 'Deixe em branco para sem limite.' : null" />
                        @endforeach
                    </div>
                @endforeach

                <div class="custom-control custom-switch mt-3">
                    <input type="checkbox" class="custom-control-input" id="mode-enabled" wire:model="form.enabled">
                    <label class="custom-control-label" for="mode-enabled">Modalidade ativa</label>
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
