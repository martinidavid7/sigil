<?php

namespace App\Livewire\CityHalls;

use App\Livewire\Concerns\Notifies;
use App\Livewire\Forms\CityHallForm;
use App\Models\CityHall;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Prefeitura')]
class Settings extends Component
{
    use Notifies;

    public CityHallForm $form;

    public function mount(): void
    {
        if ($cityHall = CityHall::first()) {
            $this->form->setCityHall($cityHall);
        }
    }

    public function save(): void
    {
        $isFirstSetup = ! $this->form->cityHall?->exists;

        $this->form->save();

        if ($isFirstSetup) {
            // Recarrega a página para liberar os demais cadastros no menu.
            $this->notifyAfterRedirect('Prefeitura cadastrada! Os demais cadastros foram liberados.');
            $this->redirectRoute('city-hall');

            return;
        }

        $this->notify('Dados da prefeitura atualizados.');
    }

    public function render()
    {
        return view('livewire.city-halls.settings');
    }
}
