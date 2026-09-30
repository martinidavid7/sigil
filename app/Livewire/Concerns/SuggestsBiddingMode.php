<?php

namespace App\Livewire\Concerns;

use App\Enums\BiddingType;
use App\Models\BiddingMode;
use App\Support\Money;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

/**
 * Escolha da modalidade no formulário da licitação (`$form`, BiddingForm),
 * com sugestão pela faixa de valor do tipo e do valor estimado informados.
 */
trait SuggestsBiddingMode
{
    #[Computed]
    public function modeOptions(): Collection
    {
        return BiddingMode::query()
            ->where(fn ($query) => $query->enabled()->orWhere('id', $this->form->bidding_mode_id ?: null))
            ->orderBy('id')
            ->pluck('name', 'id');
    }

    #[Computed]
    public function suggestedMode(): ?BiddingMode
    {
        $type = BiddingType::tryFrom($this->form->type);
        $value = preg_match(Money::PATTERN, $this->form->estimated_value)
            ? Money::parse($this->form->estimated_value)
            : null;

        if (! $type || $value === null) {
            return null;
        }

        return BiddingMode::enabled()->forValue($type, $value)->orderBy('id')->first();
    }

    public function applySuggestedMode(): void
    {
        if ($this->suggestedMode) {
            $this->form->bidding_mode_id = (string) $this->suggestedMode->id;
        }
    }
}
