<?php

namespace App\Livewire;

use App\Models\Bidding;
use App\Models\BiddingMode;
use App\Models\BiddingStage;
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
                ['label' => 'Licitações em andamento', 'value' => Bidding::inProgress()->count(), 'icon' => 'fa-folder-open', 'color' => 'warning', 'route' => 'biddings.index'],
                ['label' => 'Secretarias', 'value' => Secretary::count(), 'icon' => 'fa-building', 'color' => 'primary', 'route' => 'secretaries.index'],
                ['label' => 'Modalidades ativas', 'value' => BiddingMode::enabled()->count(), 'icon' => 'fa-balance-scale', 'color' => 'success', 'route' => 'bidding-modes.index'],
                ['label' => 'Etapas ativas', 'value' => BiddingStep::enabled()->count(), 'icon' => 'fa-list-ol', 'color' => 'info', 'route' => 'bidding-steps.index'],
            ],
            'modes' => BiddingMode::enabled()->orderBy('id')->get(),
            'steps' => BiddingStep::enabled()->ordered()->get(),
            // Quantas licitações estão paradas em cada etapa agora.
            'inProgressByStep' => BiddingStage::whereNull('completed_at')
                ->selectRaw('bidding_step_id, count(*) as total')
                ->groupBy('bidding_step_id')
                ->pluck('total', 'bidding_step_id'),
        ]);
    }
}
