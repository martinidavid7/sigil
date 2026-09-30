<?php

namespace App\Livewire\Forms;

use App\Models\Professional;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ProfessionalForm extends Form
{
    public ?Professional $professional = null;

    public string $secretary_id = '';

    public string $name = '';

    public string $role = '';

    protected function rules(): array
    {
        return [
            'secretary_id' => ['required', 'integer', Rule::exists('secretaries', 'id')],
            'name' => [
                'required', 'string', 'max:80',
                Rule::unique('professionals')->where('secretary_id', $this->secretary_id)->ignore($this->professional),
            ],
            'role' => ['nullable', 'string', 'max:80'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'secretary_id' => 'secretaria',
            'name' => 'nome',
            'role' => 'cargo',
        ];
    }

    public function setProfessional(Professional $professional): void
    {
        $this->professional = $professional;
        $this->secretary_id = (string) $professional->secretary_id;
        $this->name = $professional->name;
        $this->role = (string) $professional->role;
    }

    public function save(): Professional
    {
        $data = $this->validate();
        $data['role'] = $data['role'] ?: null;

        $this->professional ??= new Professional;
        $this->professional->fill($data)->save();

        return $this->professional;
    }
}
