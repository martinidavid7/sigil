<?php

namespace App\Livewire\Forms;

use App\Models\Secretary;
use Illuminate\Validation\Rule;
use Livewire\Form;

class SecretaryForm extends Form
{
    public ?Secretary $secretary = null;

    public string $name = '';

    public string $responsible_name = '';

    public string $phone = '';

    public string $address = '';

    public string $number = '';

    public string $neighborhood = '';

    public string $zip_code = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('secretaries')->ignore($this->secretary)],
            'responsible_name' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'min:14', 'max:15'],
            'address' => ['required', 'string', 'max:120'],
            'number' => ['required', 'string', 'max:10'],
            'neighborhood' => ['required', 'string', 'max:80'],
            'zip_code' => ['nullable', 'regex:/^\d{5}-\d{3}$/'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'secretaria',
            'responsible_name' => 'secretário(a)',
            'phone' => 'telefone',
            'address' => 'endereço',
            'number' => 'número',
            'neighborhood' => 'bairro',
            'zip_code' => 'CEP',
        ];
    }

    public function setSecretary(Secretary $secretary): void
    {
        $this->secretary = $secretary;

        $this->fill(array_map(
            fn ($value) => (string) $value,
            $secretary->only(array_keys($this->rules())),
        ));
    }

    public function save(): Secretary
    {
        $data = $this->validate();

        $this->secretary ??= new Secretary;
        $this->secretary->fill($data)->save();

        return $this->secretary;
    }
}
