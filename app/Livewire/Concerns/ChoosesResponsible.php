<?php

namespace App\Livewire\Concerns;

use App\Models\Professional;
use App\Models\Secretary;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

/**
 * Opções de secretaria e profissional responsáveis por uma etapa. A lista de
 * profissionais depende da secretaria escolhida.
 */
trait ChoosesResponsible
{
    #[Computed]
    public function secretaryOptions(): Collection
    {
        return Secretary::orderBy('name')->pluck('name', 'id');
    }

    public function professionalOptions(string $secretaryId): Collection
    {
        if ($secretaryId === '') {
            return collect();
        }

        return Professional::where('secretary_id', $secretaryId)->orderBy('name')->pluck('name', 'id');
    }

    /**
     * Trocar a secretaria limpa o profissional escolhido.
     */
    public function updatedChoosesResponsible(string $property): void
    {
        if (str_ends_with($property, '.secretary_id')) {
            data_set($this, str_replace('.secretary_id', '.professional_id', $property), '');
        }
    }
}
