<?php

namespace App\Livewire\Forms;

use App\Models\BiddingMode;
use App\Support\Money;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Form;

class BiddingModeForm extends Form
{
    /**
     * Pares de faixas de valor: [mínimo => máximo].
     */
    private const RANGES = [
        'purchase_services_minimum_value' => 'purchase_services_maximum_value',
        'construction_engineering_minimum_value' => 'construction_engineering_maximum_value',
    ];

    public ?BiddingMode $mode = null;

    public string $name = '';

    public string $deadline = '';

    // Valores no formato brasileiro ("17.600,00"), convertidos ao salvar.
    public string $purchase_services_minimum_value = '';

    public string $purchase_services_maximum_value = '';

    public string $construction_engineering_minimum_value = '';

    public string $construction_engineering_maximum_value = '';

    public bool $enabled = true;

    /** @var array<int, string> */
    public array $steps = [];

    protected function rules(): array
    {
        $money = ['nullable', 'regex:'.Money::PATTERN];

        $rules = [
            'name' => ['required', 'string', 'max:120', Rule::unique('bidding_modes')->ignore($this->mode)],
            'deadline' => ['nullable', 'string', 'max:50'],
            'enabled' => ['boolean'],
            'steps' => ['array'],
            'steps.*' => ['integer', Rule::exists('bidding_steps', 'id')],
        ];

        foreach (self::RANGES as $min => $max) {
            $rules[$min] = $money;
            $rules[$max] = [...$money, $this->notBelow($min)];
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'modalidade',
            'deadline' => 'prazo',
            'purchase_services_minimum_value' => 'valor mínimo',
            'purchase_services_maximum_value' => 'valor máximo',
            'construction_engineering_minimum_value' => 'valor mínimo',
            'construction_engineering_maximum_value' => 'valor máximo',
            'steps.*' => 'etapa',
        ];
    }

    public function setMode(BiddingMode $mode): void
    {
        $this->mode = $mode;
        $this->name = $mode->name;
        $this->deadline = (string) $mode->deadline;
        $this->enabled = $mode->enabled;
        $this->steps = $mode->steps->pluck('id')->map(fn ($id) => (string) $id)->all();

        foreach (self::RANGES as $min => $max) {
            $this->{$min} = Money::format($mode->{$min});
            $this->{$max} = Money::format($mode->{$max});
        }
    }

    public function save(): BiddingMode
    {
        $data = $this->validate();

        foreach (self::RANGES as $min => $max) {
            $data[$min] = Money::parse($data[$min]);
            $data[$max] = Money::parse($data[$max]);
        }

        $data['deadline'] = $data['deadline'] ?: null;

        return DB::transaction(function () use ($data) {
            $this->mode ??= new BiddingMode;
            $this->mode->fill($data)->save();
            $this->mode->steps()->sync($data['steps']);

            return $this->mode;
        });
    }

    /**
     * O valor máximo de uma faixa não pode ser menor que o mínimo.
     */
    private function notBelow(string $minField): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($minField) {
            $min = Money::parse($this->{$minField});
            $max = Money::parse($value);

            if ($min !== null && $max !== null && (float) $max < (float) $min) {
                $fail('O :attribute deve ser maior ou igual ao valor mínimo.');
            }
        };
    }
}
