<?php

namespace App\Livewire\BiddingSteps;

use App\Livewire\Concerns\HasFormModal;
use App\Livewire\Concerns\Notifies;
use App\Livewire\Forms\BiddingStepForm;
use App\Models\BiddingStep;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Etapas')]
class Index extends Component
{
    use HasFormModal;
    use Notifies;

    public BiddingStepForm $form;

    #[Url(as: 'situacao', except: 'ativas')]
    public string $status = 'ativas';

    public function create(): void
    {
        $this->form->reset();
        $this->openModal();
    }

    public function edit(BiddingStep $step): void
    {
        $this->form->setStep($step);
        $this->openModal();
    }

    public function save(): void
    {
        $isNew = ! $this->form->step?->exists;

        $this->form->save();
        $this->closeModal();

        $this->notify($isNew ? 'Etapa cadastrada.' : 'Etapa atualizada.');
    }

    public function toggle(BiddingStep $step): void
    {
        $step->update(['enabled' => ! $step->enabled]);

        $this->notify($step->enabled ? "{$step->name} foi reativada." : "{$step->name} foi desativada.");
    }

    public function moveUp(BiddingStep $step): void
    {
        $this->swapWith($step, BiddingStep::where('position', '<', $step->position)->orderByDesc('position'));
    }

    public function moveDown(BiddingStep $step): void
    {
        $this->swapWith($step, BiddingStep::where('position', '>', $step->position)->orderBy('position'));
    }

    /**
     * Troca a posição da etapa com a vizinha (na mesma situação) indicada pela query.
     */
    private function swapWith(BiddingStep $step, $neighbourQuery): void
    {
        $neighbour = $neighbourQuery->where('enabled', $step->enabled)->first();

        if (! $neighbour) {
            return;
        }

        DB::transaction(function () use ($step, $neighbour) {
            [$step->position, $neighbour->position] = [$neighbour->position, $step->position];
            $step->save();
            $neighbour->save();
        });
    }

    public function render()
    {
        $steps = BiddingStep::query()
            ->where('enabled', $this->status !== 'inativas')
            ->ordered()
            ->get();

        return view('livewire.bidding-steps.index', [
            'steps' => $steps,
            'disabledCount' => BiddingStep::where('enabled', false)->count(),
        ]);
    }
}
