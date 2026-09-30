<?php

namespace App\Livewire\Biddings;

use App\Livewire\Concerns\ChoosesResponsible;
use App\Livewire\Concerns\Notifies;
use App\Livewire\Concerns\SuggestsBiddingMode;
use App\Livewire\Forms\BiddingForm;
use App\Livewire\Forms\CompleteStageForm;
use App\Livewire\Forms\StageForm;
use App\Models\Bidding;
use Livewire\Component;

/**
 * Acompanhamento de uma licitação: histórico das etapas percorridas, etapa
 * atual e etapas que ainda faltam.
 */
class Show extends Component
{
    use ChoosesResponsible;
    use Notifies;
    use SuggestsBiddingMode;

    public Bidding $bidding;

    public CompleteStageForm $completion;

    public BiddingForm $form;

    public StageForm $stageForm;

    public ?string $modal = null;

    public function openCompletion(): void
    {
        if ($this->bidding->isCompleted()) {
            return;
        }

        $this->completion->setBidding($this->bidding);
        $this->openModal('completion');
    }

    public function complete(): void
    {
        $step = $this->completion->stage->step->name;
        $next = $this->completion->save();

        $this->closeModal();
        $this->bidding->refresh();

        $this->notify($next
            ? "{$step} concluída. Licitação encaminhada para {$next->step->name}."
            : "{$step} concluída. Licitação finalizada.");
    }

    public function reopen(): void
    {
        if (! $this->bidding->canReopen()) {
            return;
        }

        $stage = $this->bidding->reopenLastStage();
        $this->bidding->refresh();

        $this->notify("{$stage->step->name} foi reaberta.");
    }

    public function editStage(int $stageId): void
    {
        $this->stageForm->setStage($this->bidding->stages()->findOrFail($stageId));
        $this->openModal('stage');
    }

    public function updateStage(): void
    {
        $this->stageForm->save();
        $step = $this->stageForm->stage->step->name;

        $this->closeModal();
        $this->bidding->refresh();

        $this->notify("{$step} atualizada.");
    }

    public function edit(): void
    {
        $this->form->setBidding($this->bidding);
        $this->openModal('edit');
    }

    public function update(): void
    {
        $this->form->save();
        $this->closeModal();
        $this->bidding->refresh();

        $this->notify('Dados da licitação atualizados.');
    }

    public function delete()
    {
        $this->bidding->delete();

        $this->notifyAfterRedirect("Licitação {$this->bidding->code} excluída.");

        return $this->redirectRoute('biddings.index');
    }

    public function closeModal(): void
    {
        $this->modal = null;
        $this->completion->reset();
        $this->stageForm->reset();
        $this->form->reset();
        $this->resetValidation();
    }

    private function openModal(string $modal): void
    {
        $this->resetValidation();
        $this->modal = $modal;
    }

    public function render()
    {
        $this->bidding->load(['mode', 'stages.step', 'stages.secretary', 'stages.professional']);

        return view('livewire.biddings.show', [
            'pendingSteps' => $this->bidding->pendingSteps(),
            'canReopen' => $this->bidding->canReopen(),
        ])->title("Licitação {$this->bidding->code}");
    }
}
