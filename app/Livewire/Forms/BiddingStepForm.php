<?php

namespace App\Livewire\Forms;

use App\Models\BiddingStep;
use Illuminate\Validation\Rule;
use Livewire\Form;

class BiddingStepForm extends Form
{
    public ?BiddingStep $step = null;

    public string $name = '';

    public bool $enabled = true;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('bidding_steps')->ignore($this->step)],
            'enabled' => ['boolean'],
        ];
    }

    protected function validationAttributes(): array
    {
        return ['name' => 'etapa'];
    }

    public function setStep(BiddingStep $step): void
    {
        $this->step = $step;
        $this->name = $step->name;
        $this->enabled = $step->enabled;
    }

    public function save(): BiddingStep
    {
        $data = $this->validate();

        $this->step ??= new BiddingStep;
        $this->step->fill($data)->save();

        return $this->step;
    }
}
