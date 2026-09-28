<div>
    <x-sigil.page-header title="Prefeitura"
        subtitle="Dados do órgão responsável pelos processos licitatórios." />

    @unless ($form->cityHall?->exists)
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            Cadastre a prefeitura para liberar os demais módulos do sistema.
        </div>
    @endunless

    <form wire:submit="save" class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Identificação</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <x-sigil.input class="col-md-8" label="Nome da prefeitura" wire:model="form.name"
                    placeholder="Prefeitura Municipal de ..." />
                <x-sigil.input class="col-md-4" label="Prefeito(a)" wire:model="form.mayor" />
            </div>
            <div class="form-row">
                <x-sigil.input class="col-md-6" label="CNPJ" wire:model="form.cnpj"
                    x-mask="99.999.999/9999-99" placeholder="00.000.000/0000-00" />
                <x-sigil.input class="col-md-6" label="Inscrição estadual" wire:model="form.state_registration" />
            </div>
        </div>

        <div class="card-header py-3 border-top">
            <h6 class="m-0 font-weight-bold text-primary">Endereço e contato</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <x-sigil.input class="col-md-10" label="Endereço" wire:model="form.address" />
                <x-sigil.input class="col-md-2" label="Número" wire:model="form.number" />
            </div>
            <div class="form-row">
                <x-sigil.input class="col-md-4" label="Bairro" wire:model="form.neighborhood" />
                <x-sigil.input class="col-md-4" label="Cidade" wire:model="form.city" />
                <x-sigil.input class="col-md-2" label="CEP" wire:model="form.zip_code"
                    x-mask="99999-999" placeholder="00000-000" />
                <x-sigil.input class="col-md-2" label="Telefone" wire:model="form.phone"
                    x-mask:dynamic="$input.replace(/\D/g, '').length > 10 ? '(99) 99999-9999' : '(99) 9999-9999'"
                    placeholder="(00) 0000-0000" />
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">
                <span wire:loading.remove wire:target="save"><i class="fas fa-save mr-1"></i> Salvar</span>
                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-1"></i> Salvando...</span>
            </button>
        </div>
    </form>
</div>
