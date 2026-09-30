<?php

namespace App\Livewire\Professionals;

use App\Livewire\Concerns\HasFormModal;
use App\Livewire\Concerns\Notifies;
use App\Livewire\Forms\ProfessionalForm;
use App\Models\Professional;
use App\Models\Secretary;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Profissionais')]
class Index extends Component
{
    use HasFormModal;
    use Notifies;
    use WithPagination;

    public ProfessionalForm $form;

    #[Url(as: 'busca', except: '')]
    public string $search = '';

    #[Url(as: 'secretaria', except: '')]
    public string $secretary = '';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'secretary'])) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function secretaries()
    {
        return Secretary::orderBy('name')->get(['id', 'name']);
    }

    public function create(): void
    {
        $this->form->reset();
        $this->form->secretary_id = $this->secretary;
        $this->openModal();
    }

    public function edit(Professional $professional): void
    {
        $this->form->setProfessional($professional);
        $this->openModal();
    }

    public function save(): void
    {
        $isNew = ! $this->form->professional?->exists;

        $this->form->save();
        $this->closeModal();

        $this->notify($isNew ? 'Profissional cadastrado(a).' : 'Profissional atualizado(a).');
    }

    public function delete(Professional $professional): void
    {
        if ($professional->stages()->exists()) {
            $this->notify("{$professional->name} já responde por etapas de licitações e não pode ser excluído(a).", 'error');

            return;
        }

        $professional->delete();

        $this->notify("{$professional->name} foi excluído(a).");
    }

    public function render()
    {
        $professionals = Professional::query()
            ->with('secretary')
            ->when($this->secretary, fn ($query, $id) => $query->where('secretary_id', $id))
            ->when($this->search, fn ($query, $term) => $query->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('role', 'like', "%{$term}%");
            }))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.professionals.index', compact('professionals'));
    }
}
