<?php

namespace App\Livewire\Forms;

use App\Models\Bidding;
use App\Models\BiddingStage;
use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Conclusão da etapa atual de uma licitação e indicação dos responsáveis
 * pela etapa seguinte.
 */
class CompleteStageForm extends Form
{
    public ?BiddingStage $stage = null;

    public bool $hasNextStep = false;

    public string $completed_at = '';

    public string $page_number = '';

    public string $notes = '';

    public string $secretary_id = '';

    public string $professional_id = '';

    protected function rules(): array
    {
        $rules = [
            'completed_at' => [
                'required', 'date', 'before_or_equal:today',
                'after_or_equal:'.$this->stage->started_at->toDateString(),
            ],
            'page_number' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];

        if (! $this->hasNextStep) {
            return $rules;
        }

        return $rules + [
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
            'completed_at' => 'data de conclusão',
            'page_number' => 'número da página',
            'notes' => 'observações',
            'secretary_id' => 'nova secretaria responsável',
            'professional_id' => 'novo profissional responsável',
        ];
    }

    protected function messages(): array
    {
        return [
            'completed_at.before_or_equal' => 'A data de conclusão não pode ser futura.',
            'completed_at.after_or_equal' => 'A data de conclusão não pode ser anterior ao início da etapa ('
                .$this->stage->started_at->format('d/m/Y').').',
        ];
    }

    public function setBidding(Bidding $bidding): void
    {
        $this->reset();

        $this->stage = $bidding->currentStage()->firstOrFail();
        $this->hasNextStep = $bidding->pendingSteps()->isNotEmpty();
        $this->completed_at = today()->toDateString();
        $this->page_number = (string) $this->stage->page_number;
        $this->notes = (string) $this->stage->notes;
    }

    public function save(): ?BiddingStage
    {
        // O cadastro de etapas pode ter mudado desde que o formulário foi aberto.
        $this->hasNextStep = $this->stage->bidding->pendingSteps()->isNotEmpty();

        $data = $this->validate();

        return $this->stage->bidding->completeCurrentStage(
            [
                'completed_at' => $data['completed_at'],
                'page_number' => $data['page_number'] ?: null,
                'notes' => $data['notes'] ?: null,
            ],
            $this->hasNextStep ? [
                'secretary_id' => $data['secretary_id'],
                'professional_id' => $data['professional_id'],
            ] : null,
        );
    }
}
