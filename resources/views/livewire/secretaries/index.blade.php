<div>
    <x-sigil.page-header title="Secretarias" subtitle="Órgãos requisitantes dos processos de compra.">
        <x-slot:actions>
            <button type="button" class="btn btn-primary shadow-sm" wire:click="create">
                <i class="fas fa-plus fa-sm mr-1"></i> Nova secretaria
            </button>
        </x-slot:actions>
    </x-sigil.page-header>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="input-group" style="max-width: 360px">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-gray-400"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Buscar por secretaria ou responsável"
                    aria-label="Buscar" wire:model.live.debounce.300ms="search">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Secretaria</th>
                        <th>Responsável</th>
                        <th>Telefone</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($secretaries as $secretary)
                        <tr wire:key="secretary-{{ $secretary->id }}">
                            <td class="align-middle font-weight-bold text-gray-800">{{ $secretary->name }}</td>
                            <td class="align-middle">{{ $secretary->responsible_name }}</td>
                            <td class="align-middle text-nowrap">{{ $secretary->phone }}</td>
                            <td class="align-middle text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="edit({{ $secretary->id }})">
                                    <i class="fas fa-pen fa-sm"></i> Editar
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    wire:click="delete({{ $secretary->id }})"
                                    wire:confirm="Excluir a {{ $secretary->name }}? Esta ação não pode ser desfeita.">
                                    <i class="fas fa-trash fa-sm"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                @if ($search)
                                    Nenhuma secretaria encontrada para "{{ $search }}".
                                @else
                                    Nenhuma secretaria cadastrada ainda.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($secretaries->hasPages())
            <div class="card-footer bg-white">{{ $secretaries->links() }}</div>
        @endif
    </div>

    <x-sigil.modal :show="$showModal" :title="$form->secretary?->exists ? 'Editar secretaria' : 'Nova secretaria'">
        <form wire:submit="save">
            <div class="modal-body">
                <x-sigil.input label="Secretaria" wire:model="form.name" placeholder="Secretaria de ..." />
                <div class="form-row">
                    <x-sigil.input class="col-md-8" label="Secretário(a)" wire:model="form.responsible_name" />
                    <x-sigil.input class="col-md-4" label="Telefone" wire:model="form.phone"
                        x-mask:dynamic="$input.replace(/\D/g, '').length > 10 ? '(99) 99999-9999' : '(99) 9999-9999'"
                        placeholder="(00) 0000-0000" />
                </div>
                <div class="form-row">
                    <x-sigil.input class="col-md-10" label="Endereço" wire:model="form.address" />
                    <x-sigil.input class="col-md-2" label="Número" wire:model="form.number" />
                </div>
                <div class="form-row">
                    <x-sigil.input class="col-md-8" label="Bairro" wire:model="form.neighborhood" />
                    <x-sigil.input class="col-md-4" label="CEP" wire:model="form.zip_code"
                        x-mask="99999-999" placeholder="00000-000" />
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
