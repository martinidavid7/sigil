<?php

namespace App\Livewire\BiddingModes;

use App\Livewire\Concerns\HasFormModal;
use App\Livewire\Concerns\Notifies;
use App\Livewire\Forms\BiddingModeForm;
use App\Models\BiddingMode;
use App\Models\BiddingStep;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Modalidades')]
class Index extends Component
{
    use HasFormModal;
    use Notifies;

    public BiddingModeForm $form;

    #[Computed]
    public function availableSteps()
    {
        return BiddingStep::enabled()->ordered()->get();
    }

    public function create(): void
    {
        $this->form->reset();
        $this->form->steps = $this->availableSteps->pluck('id')->map(fn ($id) => (string) $id)->all();
        $this->openModal();
    }

    public function edit(BiddingMode $mode): void
    {
        $this->form->setMode($mode->load('steps'));
        $this->openModal();
    }

    public function save(): void
    {
        $isNew = ! $this->form->mode?->exists;

        $this->form->save();
        $this->closeModal();

        $this->notify($isNew ? 'Modalidade cadastrada.' : 'Modalidade atualizada.');
    }

    public function toggle(BiddingMode $mode): void
    {
        $mode->update(['enabled' => ! $mode->enabled]);

        $this->notify($mode->enabled ? "{$mode->name} foi ativada." : "{$mode->name} foi desativada.");
    }

    public function delete(BiddingMode $mode): void
    {
        $mode->delete();

        $this->notify("{$mode->name} foi excluída.");
    }

    public function render()
    {
        return view('livewire.bidding-modes.index', [
            'modes' => BiddingMode::withCount('steps')->orderBy('id')->get(),
        ]);
    }
}
