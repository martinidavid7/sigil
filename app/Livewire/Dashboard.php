<?php

namespace App\Livewire;

use App\Models\BiddingMode;
use App\Models\BiddingStep;
use App\Models\CityHall;
use App\Models\Secretary;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Painel')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'cityHall' => CityHall::first(),
            'stats' => [
                ['label' => 'Secretarias', 'value' => Secretary::count(), 'icon' => 'fa-building', 'color' => 'primary', 'route' => 'secretaries.index'],
                ['label' => 'Modalidades ativas', 'value' => BiddingMode::enabled()->count(), 'icon' => 'fa-balance-scale', 'color' => 'success', 'route' => 'bidding-modes.index'],
                ['label' => 'Etapas ativas', 'value' => BiddingStep::enabled()->count(), 'icon' => 'fa-list-ol', 'color' => 'info', 'route' => 'bidding-steps.index'],
            ],
            'modes' => BiddingMode::enabled()->orderBy('id')->get(),
            'steps' => BiddingStep::enabled()->ordered()->get(),
        ]);
    }
}
