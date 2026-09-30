<?php

namespace App\Livewire\BiddingModes;

use App\Livewire\Concerns\HasFormModal;
use App\Livewire\Concerns\Notifies;
use App\Livewire\Forms\BiddingModeForm;
use App\Models\BiddingMode;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Modalidades')]
class Index extends Component
{
    use HasFormModal;
    use Notifies;

    public BiddingModeForm $form;

    public function create(): void
    {
        $this->form->reset();
        $this->openModal();
    }

    public function edit(BiddingMode $mode): void
    {
        $this->form->setMode($mode);
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
        if ($mode->biddings()->exists()) {
            $this->notify("{$mode->name} já é usada em licitações. Desative-a em vez de excluir.", 'error');

            return;
        }

        $mode->delete();

        $this->notify("{$mode->name} foi excluída.");
    }

    public function render()
    {
        return view('livewire.bidding-modes.index', [
            'modes' => BiddingMode::orderBy('id')->get(),
        ]);
    }
}
