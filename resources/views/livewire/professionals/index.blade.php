<div>
    <x-sigil.page-header title="Profissionais" subtitle="Servidores das secretarias que respondem pelas etapas das licitações.">
        <x-slot:actions>
            <button type="button" class="btn btn-primary shadow-sm" wire:click="create">
                <i class="fas fa-plus fa-sm mr-1"></i> Novo profissional
            </button>
        </x-slot:actions>
    </x-sigil.page-header>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-md-flex">
            <div class="input-group mb-2 mb-md-0 mr-md-2" style="max-width: 360px">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-gray-400"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Buscar por nome ou cargo"
                    aria-label="Buscar" wire:model.live.debounce.300ms="search">
            </div>
            <select class="custom-select" style="max-width: 320px" aria-label="Filtrar por secretaria"
                wire:model.live="secretary">
                <option value="">Todas as secretarias</option>
                @foreach ($this->secretaries as $option)
                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nome</th>
                        <th>Cargo</th>
                        <th>Secretaria</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($professionals as $professional)
                        <tr wire:key="professional-{{ $professional->id }}">
                            <td class="align-middle font-weight-bold text-gray-800">{{ $professional->name }}</td>
                            <td class="align-middle">{{ $professional->role ?? '—' }}</td>
                            <td class="align-middle">{{ $professional->secretary->name }}</td>
                            <td class="align-middle text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="edit({{ $professional->id }})">
                                    <i class="fas fa-pen fa-sm"></i> Editar
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    wire:click="delete({{ $professional->id }})"
                                    wire:confirm="Excluir {{ $professional->name }}? Esta ação não pode ser desfeita.">
                                    <i class="fas fa-trash fa-sm"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                @if ($search || $secretary)
                                    Nenhum profissional encontrado com esses filtros.
                                @else
                                    Nenhum profissional cadastrado ainda.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($professionals->hasPages())
            <div class="card-footer bg-white">{{ $professionals->links() }}</div>
        @endif
    </div>

    <x-sigil.modal :show="$showModal" :title="$form->professional?->exists ? 'Editar profissional' : 'Novo profissional'">
        <form wire:submit="save">
            <div class="modal-body">
                <x-sigil.select label="Secretaria" wire:model="form.secretary_id"
                    :options="$this->secretaries->pluck('name', 'id')" />
                <div class="form-row">
                    <x-sigil.input class="col-md-7" label="Nome" wire:model="form.name" />
                    <x-sigil.input class="col-md-5" label="Cargo" wire:model="form.role" placeholder="Opcional" />
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
