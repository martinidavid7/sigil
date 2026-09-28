<?php

namespace App\Livewire\Concerns;

/**
 * Controle do modal de criação/edição usado pelas telas de cadastro.
 * O componente precisa ter uma propriedade pública `$form` (Livewire Form).
 */
trait HasFormModal
{
    public bool $showModal = false;

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->form->reset();
        $this->resetValidation();
    }

    protected function openModal(): void
    {
        $this->resetValidation();
        $this->showModal = true;
    }
}
