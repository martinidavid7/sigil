<?php

namespace App\Livewire\Biddings;

use App\Livewire\Concerns\ChoosesResponsible;
use App\Livewire\Concerns\HasFormModal;
use App\Livewire\Concerns\Notifies;
use App\Livewire\Concerns\SuggestsBiddingMode;
use App\Livewire\Forms\BiddingForm;
use App\Models\Bidding;
use App\Models\BiddingStep;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Licitações')]
class Index extends Component
{
    use ChoosesResponsible;
    use HasFormModal;
    use Notifies;
    use SuggestsBiddingMode;
    use WithPagination;

    public BiddingForm $form;

    #[Url(as: 'busca', except: '')]
    public string $search = '';

    #[Url(as: 'situacao', except: 'andamento')]
    public string $status = 'andamento';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'status'])) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function firstStep(): ?BiddingStep
    {
        return BiddingStep::enabled()->ordered()->first();
    }

    public function create(): void
    {
        if (! $this->firstStep) {
            $this->notify('Cadastre ao menos uma etapa ativa antes de abrir uma licitação.', 'error');

            return;
        }

        $this->form->reset();
        $this->openModal();
    }

    public function save()
    {
        $bidding = $this->form->save();

        $this->notifyAfterRedirect("Licitação {$bidding->code} aberta.");

        return $this->redirectRoute('biddings.show', $bidding);
    }

    public function render()
    {
        $biddings = Bidding::query()
            ->with(['mode', 'currentStage.step', 'currentStage.secretary', 'currentStage.professional'])
            ->when($this->status === 'andamento', fn ($query) => $query->inProgress())
            ->when($this->status === 'concluidas', fn ($query) => $query->completed())
            ->when($this->search, function ($query, $term) {
                // "12" ou "12/2026" busca pelo número do processo; o resto, pelo objeto.
                if (preg_match('#^(\d+)(?:/(\d{4}))?$#', trim($term), $match)) {
                    $query->where('number', (int) $match[1])
                        ->when($match[2] ?? null, fn ($query, $year) => $query->where('year', $year));
                } else {
                    $query->where('subject', 'like', "%{$term}%");
                }
            })
            ->orderByDesc('year')
            ->orderByDesc('number')
            ->paginate(10);

        return view('livewire.biddings.index', compact('biddings'));
    }
}
