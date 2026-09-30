<?php

namespace App\Livewire\Forms;

use App\Models\BiddingStage;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Edição de uma etapa já iniciada. A data de início só é editável na
 * primeira etapa; nas demais ela é a conclusão da etapa anterior.
 */
class StageForm extends Form
{
    public ?BiddingStage $stage = null;

    public string $started_at = '';

    public string $completed_at = '';

    public string $secretary_id = '';

    public string $professional_id = '';

    public string $page_number = '';

    public string $notes = '';

    protected function rules(): array
    {
        $rules = [
            'secretary_id' => ['required', 'integer', Rule::exists('secretaries', 'id')],
            'professional_id' => [
                'required', 'integer',
                Rule::exists('professionals', 'id')->where('secretary_id', $this->secretary_id),
            ],
            'page_number' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];

        if ($this->canEditStart()) {
            $rules['started_at'] = ['required', 'date', 'before_or_equal:today'];
        }

        if ($this->stage->isCompleted()) {
            $rules['completed_at'] = ['required', 'date', 'before_or_equal:'.$this->completionLimit()];

            if ($this->isDate($this->started_at)) {
                $rules['completed_at'][] = 'after_or_equal:'.$this->started_at;
            }
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        return [
            'started_at' => 'data de início',
            'completed_at' => 'data de conclusão',
            'secretary_id' => 'secretaria responsável',
            'professional_id' => 'profissional responsável',
            'page_number' => 'número da página',
            'notes' => 'observações',
        ];
    }

    protected function messages(): array
    {
        $limit = $this->completionLimit();

        return [
            'started_at.before_or_equal' => 'A data de início não pode ser futura.',
            'completed_at.after_or_equal' => 'A data de conclusão não pode ser anterior ao início da etapa ('
                .$this->display($this->started_at).').',
            'completed_at.before_or_equal' => $limit === 'today'
                ? 'A data de conclusão não pode ser futura.'
                : 'A data de conclusão não pode ser posterior à conclusão da etapa seguinte ('.$this->display($limit).').',
        ];
    }

    public function setStage(BiddingStage $stage): void
    {
        $this->reset();

        $this->stage = $stage;
        $this->started_at = $stage->started_at->toDateString();
        $this->completed_at = (string) $stage->completed_at?->toDateString();
        $this->secretary_id = (string) $stage->secretary_id;
        $this->professional_id = (string) $stage->professional_id;
        $this->page_number = (string) $stage->page_number;
        $this->notes = (string) $stage->notes;
    }

    public function canEditStart(): bool
    {
        return $this->stage->previous() === null;
    }

    public function save(): void
    {
        $data = $this->validate();

        $data['page_number'] = $data['page_number'] ?: null;
        $data['notes'] = $data['notes'] ?: null;

        $this->stage->revise($data);
    }

    /**
     * A conclusão é o início da etapa seguinte, então não pode passar da
     * conclusão dela (nem de hoje).
     */
    private function completionLimit(): string
    {
        return $this->stage->next()?->completed_at?->toDateString() ?? 'today';
    }

    private function isDate(string $value): bool
    {
        return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);
    }

    private function display(string $date): string
    {
        return $this->isDate($date) ? Carbon::parse($date)->format('d/m/Y') : $date;
    }
}
