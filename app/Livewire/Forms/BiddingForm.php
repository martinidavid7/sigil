<?php

namespace App\Livewire\Forms;

use App\Enums\BiddingType;
use App\Models\Bidding;
use App\Support\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Dados da licitação. Na abertura, inclui também os responsáveis e a data
 * de início da primeira etapa.
 */
class BiddingForm extends Form
{
    public ?Bidding $bidding = null;

    public string $number = '';

    public string $year = '';

    public string $subject = '';

    public string $type = BiddingType::PurchaseServices->value;

    // Valor no formato brasileiro ("17.600,00"), convertido ao salvar.
    public string $estimated_value = '';

    public string $bidding_mode_id = '';

    public string $started_at = '';

    public string $secretary_id = '';

    public string $professional_id = '';

    protected function rules(): array
    {
        $rules = [
            'number' => [
                'required', 'integer', 'min:1',
                Rule::unique('biddings')->where('year', $this->year)->ignore($this->bidding),
            ],
            'year' => ['required', 'integer', 'between:2000,2100'],
            'subject' => ['required', 'string', 'max:2000'],
            'type' => ['required', Rule::enum(BiddingType::class)],
            'estimated_value' => ['nullable', 'regex:'.Money::PATTERN],
            'bidding_mode_id' => ['required', 'integer', Rule::exists('bidding_modes', 'id')],
        ];

        if ($this->bidding?->exists) {
            return $rules;
        }

        return $rules + [
            'started_at' => ['required', 'date', 'before_or_equal:today'],
            'secretary_id' => ['required', 'integer', Rule::exists('secretaries', 'id')],
            'professional_id' => [
                'required', 'integer',
                Rule::exists('professionals', 'id')->where('secretary_id', $this->secretary_id),
            ],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'number' => 'número',
            'year' => 'ano',
            'subject' => 'objeto',
            'type' => 'tipo',
            'estimated_value' => 'valor estimado',
            'bidding_mode_id' => 'modalidade',
            'started_at' => 'data de início',
            'secretary_id' => 'secretaria responsável',
            'professional_id' => 'profissional responsável',
        ];
    }

    protected function messages(): array
    {
        return [
            'number.unique' => 'Já existe uma licitação com este número no ano informado.',
            'started_at.before_or_equal' => 'A data de início não pode ser futura.',
        ];
    }

    public function reset(...$properties): void
    {
        parent::reset(...$properties);

        if (! $properties) {
            $this->year = (string) today()->year;
            $this->number = (string) Bidding::nextNumber(today()->year);
            $this->started_at = today()->toDateString();
        }
    }

    public function setBidding(Bidding $bidding): void
    {
        $this->bidding = $bidding;
        $this->number = (string) $bidding->number;
        $this->year = (string) $bidding->year;
        $this->subject = $bidding->subject;
        $this->type = $bidding->type->value;
        $this->estimated_value = Money::format($bidding->estimated_value);
        $this->bidding_mode_id = (string) $bidding->bidding_mode_id;
    }

    public function save(): Bidding
    {
        $data = $this->validate();
        $data['estimated_value'] = Money::parse($data['estimated_value']);

        if ($this->bidding?->exists) {
            $this->bidding->update($data);

            return $this->bidding;
        }

        return DB::transaction(function () use ($data) {
            $this->bidding = Bidding::create($data);
            $this->bidding->begin([
                'started_at' => $data['started_at'],
                'secretary_id' => $data['secretary_id'],
                'professional_id' => $data['professional_id'],
            ]);

            return $this->bidding;
        });
    }
}
