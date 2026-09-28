<?php

namespace App\Livewire\Secretaries;

use App\Livewire\Concerns\HasFormModal;
use App\Livewire\Concerns\Notifies;
use App\Livewire\Forms\SecretaryForm;
use App\Models\Secretary;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Secretarias')]
class Index extends Component
{
    use HasFormModal;
    use Notifies;
    use WithPagination;

    public SecretaryForm $form;

    #[Url(as: 'busca', except: '')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->form->reset();
        $this->openModal();
    }

    public function edit(Secretary $secretary): void
    {
        $this->form->setSecretary($secretary);
        $this->openModal();
    }

    public function save(): void
    {
        $isNew = ! $this->form->secretary?->exists;

        $this->form->save();
        $this->closeModal();

        $this->notify($isNew ? 'Secretaria cadastrada.' : 'Secretaria atualizada.');
    }

    public function delete(Secretary $secretary): void
    {
        $secretary->delete();

        $this->notify("{$secretary->name} foi excluída.");
    }

    public function render()
    {
        $secretaries = Secretary::query()
            ->when($this->search, fn ($query, $term) => $query->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('responsible_name', 'like', "%{$term}%");
            }))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.secretaries.index', compact('secretaries'));
    }
}
