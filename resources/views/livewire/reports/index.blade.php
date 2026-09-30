<div>
    <x-sigil.page-header title="Relatórios" subtitle="Escolha os filtros e gere o PDF. Ele abre em uma nova aba." />

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex align-items-center">
                    <i class="fas fa-folder-open text-primary mr-2"></i>
                    <h6 class="m-0 font-weight-bold text-primary">Processos</h6>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="small text-muted">
                        Licitações com a etapa atual, o responsável, há quantos dias estão na etapa e o valor estimado total.
                    </p>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="biddings_status">Situação</label>
                            <select id="biddings_status" class="custom-select" wire:model.live="biddings.status">
                                @foreach ($biddingStatuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-sigil.select class="col-md-6" label="Ano" wire:model.live="biddings.year"
                            :options="$years" placeholder="Todos" />
                    </div>
                    <x-sigil.select label="Modalidade" wire:model.live="biddings.bidding_mode_id"
                        :options="$modes" placeholder="Todas" />
                    <x-sigil.select label="Secretaria atual" wire:model.live="biddings.secretary_id"
                        :options="$this->secretaryOptions" placeholder="Todas"
                        hint="Secretaria responsável pela etapa em andamento." />

                    <div class="mt-auto text-right">
                        <a href="{{ $this->reportUrl('biddings') }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf mr-1"></i> Gerar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex align-items-center">
                    <i class="fas fa-stream text-primary mr-2"></i>
                    <h6 class="m-0 font-weight-bold text-primary">Etapas</h6>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="small text-muted">
                        Etapas que estiveram em andamento no período, com responsáveis, datas, páginas, observações
                        e o tempo médio de cada etapa.
                    </p>

                    <div class="form-row">
                        <x-sigil.input class="col-md-6" type="date" label="De" wire:model.live="stages.from" />
                        <x-sigil.input class="col-md-6" type="date" label="Até" wire:model.live="stages.to" />
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stages_status">Situação</label>
                            <select id="stages_status" class="custom-select" wire:model.live="stages.status">
                                @foreach ($stageStatuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-sigil.select class="col-md-6" label="Etapa" wire:model.live="stages.bidding_step_id"
                            :options="$steps" placeholder="Todas" />
                    </div>
                    <div class="form-row">
                        <x-sigil.select class="col-md-6" label="Secretaria" wire:model.live="stages.secretary_id"
                            :options="$this->secretaryOptions" placeholder="Todas" />
                        <x-sigil.select class="col-md-6" label="Profissional" wire:model.live="stages.professional_id"
                            :options="$this->professionalOptions($stages['secretary_id'])"
                            :placeholder="$stages['secretary_id'] ? 'Todos' : 'Escolha a secretaria'" />
                    </div>

                    <div class="mt-auto text-right">
                        <a href="{{ $this->reportUrl('stages') }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf mr-1"></i> Gerar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
