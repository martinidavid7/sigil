@use('App\Support\Money')

@php
    $stages = $bidding->stages;
    $completedCount = $stages->filter->isCompleted()->count();
    $total = $stages->count() + $pendingSteps->count();
    $lastCompleted = $stages->filter->isCompleted()->last();
@endphp

<div>
    <x-sigil.page-header :title="'Licitação '.$bidding->code" :subtitle="$bidding->mode->name">
        <x-slot:actions>
            <a href="{{ route('biddings.index') }}" class="btn btn-light shadow-sm">
                <i class="fas fa-arrow-left fa-sm mr-1"></i> Voltar
            </a>
            <button type="button" class="btn btn-outline-primary shadow-sm" wire:click="edit">
                <i class="fas fa-pen fa-sm mr-1"></i> Editar
            </button>
            <button type="button" class="btn btn-outline-danger shadow-sm" wire:click="delete"
                wire:confirm="Excluir a licitação {{ $bidding->code }} e todo o seu histórico? Esta ação não pode ser desfeita.">
                <i class="fas fa-trash fa-sm mr-1"></i> Excluir
            </button>
        </x-slot:actions>
    </x-sigil.page-header>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Objeto</h6>
                </div>
                <div class="card-body">
                    <p class="text-gray-800 mb-3" style="white-space: pre-line">{{ $bidding->subject }}</p>
                    <dl class="row small mb-0">
                        <dt class="col-sm-3 text-muted">Tipo</dt>
                        <dd class="col-sm-9">{{ $bidding->type->label() }}</dd>
                        <dt class="col-sm-3 text-muted">Valor estimado</dt>
                        <dd class="col-sm-9">{{ Money::display($bidding->estimated_value) }}</dd>
                        <dt class="col-sm-3 text-muted">Modalidade</dt>
                        <dd class="col-sm-9 mb-0">{{ $bidding->mode->name }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div @class(['card shadow h-100', 'border-left-success' => $bidding->isCompleted(), 'border-left-primary' => ! $bidding->isCompleted()])>
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 {{ $bidding->isCompleted() ? 'text-success' : 'text-primary' }}">
                        {{ $bidding->isCompleted() ? 'Concluída' : 'Em andamento' }}
                    </div>
                    <div class="h5 font-weight-bold text-gray-800 mb-1">
                        {{ $completedCount }} de {{ $total }} etapas concluídas
                    </div>
                    <div class="progress progress-sm mb-3">
                        <div class="progress-bar {{ $bidding->isCompleted() ? 'bg-success' : '' }}" role="progressbar"
                            style="width: {{ $total ? round($completedCount / $total * 100) : 0 }}%"
                            aria-valuenow="{{ $completedCount }}" aria-valuemin="0" aria-valuemax="{{ $total }}"></div>
                    </div>

                    @if ($stage = $bidding->currentStage)
                        <div class="small text-muted">Etapa atual</div>
                        <div class="font-weight-bold text-gray-800">{{ $stage->step->name }}</div>
                        <div class="small mb-3">
                            {{ $stage->professional->name }} · {{ $stage->secretary->name }}<br>
                            desde {{ $stage->started_at->format('d/m/Y') }}
                            ({{ trans_choice(':count dia|:count dias', $stage->days()) }})
                        </div>
                        <button type="button" class="btn btn-success btn-block" wire:click="openCompletion">
                            <i class="fas fa-check mr-1"></i> Concluir etapa
                        </button>
                    @else
                        <div class="small">
                            Finalizada em {{ $bidding->completed_at->format('d/m/Y') }},
                            após {{ trans_choice(':count dia|:count dias', (int) $stages->first()->started_at->diffInDays($bidding->completed_at)) }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Etapas do processo</h6>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px"></th>
                        <th>Etapa</th>
                        <th>Início</th>
                        <th>Conclusão</th>
                        <th>Responsável</th>
                        <th>Página</th>
                        <th>Observações</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stages as $stage)
                        <tr wire:key="stage-{{ $stage->id }}" @class(['table-primary' => ! $stage->isCompleted()])>
                            <td class="align-middle text-center">
                                @if ($stage->isCompleted())
                                    <i class="fas fa-check-circle text-success" title="Concluída"></i>
                                @else
                                    <i class="fas fa-play-circle text-primary" title="Em andamento"></i>
                                @endif
                            </td>
                            <td class="align-middle font-weight-bold text-gray-800">
                                {{ $loop->iteration }}. {{ $stage->step->name }}
                                @unless ($stage->step->enabled)
                                    <span class="badge badge-secondary ml-1" title="Etapa desativada no cadastro">inativa</span>
                                @endunless
                            </td>
                            <td class="align-middle text-nowrap">{{ $stage->started_at->format('d/m/Y') }}</td>
                            <td class="align-middle text-nowrap">
                                @if ($stage->isCompleted())
                                    {{ $stage->completed_at->format('d/m/Y') }}
                                    <div class="small text-muted">{{ trans_choice(':count dia|:count dias', $stage->days()) }}</div>
                                @else
                                    <span class="badge badge-primary">Em andamento</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                {{ $stage->professional->name }}
                                <div class="small text-muted">{{ $stage->secretary->name }}</div>
                            </td>
                            <td class="align-middle">{{ $stage->page_number ?? '—' }}</td>
                            <td class="align-middle small" style="white-space: pre-line">{{ $stage->notes ?? '—' }}</td>
                            <td class="align-middle text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="editStage({{ $stage->id }})" title="Editar etapa">
                                    <i class="fas fa-pen fa-sm"></i> Editar
                                </button>
                                @if (! $stage->isCompleted())
                                    <button type="button" class="btn btn-sm btn-success" wire:click="openCompletion">
                                        <i class="fas fa-check fa-sm"></i> Concluir
                                    </button>
                                @elseif ($canReopen && $stage->is($lastCompleted))
                                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="reopen"
                                        wire:confirm="Reabrir {{ $stage->step->name }}?{{ $bidding->isCompleted() ? '' : ' A etapa seguinte, em andamento, será descartada.' }}"
                                        title="Reabrir etapa">
                                        <i class="fas fa-undo fa-sm"></i> Reabrir
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    @foreach ($pendingSteps as $step)
                        <tr wire:key="pending-{{ $step->id }}" class="text-muted">
                            <td class="align-middle text-center"><i class="far fa-circle"></i></td>
                            <td class="align-middle">{{ $stages->count() + $loop->iteration }}. {{ $step->name }}</td>
                            <td colspan="6" class="align-middle small">Pendente</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($modal === 'completion' && $completion->stage)
        <x-sigil.modal :show="true" :title="'Concluir etapa: '.$completion->stage->step->name">
            <form wire:submit="complete">
                <div class="modal-body">
                    <p class="small text-muted">
                        Em andamento desde {{ $completion->stage->started_at->format('d/m/Y') }},
                        com {{ $completion->stage->professional->name }} ({{ $completion->stage->secretary->name }}).
                    </p>

                    <div class="form-row">
                        <x-sigil.input class="col-md-6" type="date" label="Data de conclusão" wire:model="completion.completed_at"
                            min="{{ $completion->stage->started_at->toDateString() }}" max="{{ today()->toDateString() }}" />
                        <x-sigil.input class="col-md-6" label="Número da página" wire:model="completion.page_number"
                            placeholder="Ex.: 23" />
                    </div>

                    <div class="form-group">
                        <label for="completion_notes">Observações</label>
                        <textarea id="completion_notes" rows="3" wire:model="completion.notes"
                            @class(['form-control', 'is-invalid' => $errors->has('completion.notes')])></textarea>
                        @error('completion.notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($completion->hasNextStep && $next = $pendingSteps->first())
                        <h6 class="font-weight-bold text-primary mt-2">Encaminhar para: {{ $next->name }}</h6>
                        <div class="form-row">
                            <x-sigil.select class="col-md-6" label="Nova secretaria responsável"
                                wire:model.live="completion.secretary_id" :options="$this->secretaryOptions" />
                            <x-sigil.select class="col-md-6" label="Novo profissional responsável"
                                wire:model="completion.professional_id"
                                :options="$this->professionalOptions($completion->secretary_id)"
                                :placeholder="$completion->secretary_id ? 'Selecione...' : 'Escolha a secretaria primeiro'"
                                :hint="$completion->secretary_id && $this->professionalOptions($completion->secretary_id)->isEmpty() ? 'Nenhum profissional cadastrado nesta secretaria.' : null" />
                        </div>
                    @else
                        <div class="alert alert-success small mb-0">
                            <i class="fas fa-flag-checkered mr-1"></i>
                            Esta é a última etapa: ao concluí-la, a licitação será finalizada.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="complete">
                        <i class="fas fa-check mr-1"></i> Concluir etapa
                    </button>
                </div>
            </form>
        </x-sigil.modal>
    @endif

    @if ($modal === 'stage' && $stageForm->stage)
        <x-sigil.modal :show="true" :title="'Editar etapa: '.$stageForm->stage->step->name">
            <form wire:submit="updateStage">
                <div class="modal-body">
                    <div class="form-row">
                        @if ($stageForm->canEditStart())
                            <x-sigil.input class="col-md-6" type="date" label="Data de início"
                                wire:model="stageForm.started_at" max="{{ today()->toDateString() }}" />
                        @else
                            <x-sigil.input class="col-md-6" type="date" label="Data de início"
                                wire:model="stageForm.started_at" disabled
                                hint="Definida pela conclusão da etapa anterior." />
                        @endif

                        @if ($stageForm->stage->isCompleted())
                            <x-sigil.input class="col-md-6" type="date" label="Data de conclusão"
                                wire:model="stageForm.completed_at"
                                :hint="$stageForm->stage->next() ? 'Também é o início da etapa seguinte.' : null" />
                        @endif
                    </div>

                    <div class="form-row">
                        <x-sigil.select class="col-md-6" label="Secretaria responsável"
                            wire:model.live="stageForm.secretary_id" :options="$this->secretaryOptions" />
                        <x-sigil.select class="col-md-6" label="Profissional responsável"
                            wire:model="stageForm.professional_id"
                            :options="$this->professionalOptions($stageForm->secretary_id)"
                            :placeholder="$stageForm->secretary_id ? 'Selecione...' : 'Escolha a secretaria primeiro'"
                            :hint="$stageForm->secretary_id && $this->professionalOptions($stageForm->secretary_id)->isEmpty() ? 'Nenhum profissional cadastrado nesta secretaria.' : null" />
                    </div>

                    <x-sigil.input label="Número da página" wire:model="stageForm.page_number" placeholder="Ex.: 23" />

                    <div class="form-group mb-0">
                        <label for="stageForm_notes">Observações</label>
                        <textarea id="stageForm_notes" rows="3" wire:model="stageForm.notes"
                            @class(['form-control', 'is-invalid' => $errors->has('stageForm.notes')])></textarea>
                        @error('stageForm.notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="updateStage">
                        <i class="fas fa-save mr-1"></i> Salvar
                    </button>
                </div>
            </form>
        </x-sigil.modal>
    @endif

    <x-sigil.modal :show="$modal === 'edit'" title="Editar licitação">
        <form wire:submit="update">
            <div class="modal-body">
                @include('livewire.biddings.partials.bidding-fields')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update">
                    <i class="fas fa-save mr-1"></i> Salvar
                </button>
            </div>
        </form>
    </x-sigil.modal>
</div>
