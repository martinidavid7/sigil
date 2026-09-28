<?php

namespace App\Livewire\Forms;

use App\Models\CityHall;
use Livewire\Form;

class CityHallForm extends Form
{
    public ?CityHall $cityHall = null;

    public string $name = '';

    public string $mayor = '';

    public string $cnpj = '';

    public string $state_registration = '';

    public string $address = '';

    public string $number = '';

    public string $neighborhood = '';

    public string $city = '';

    public string $zip_code = '';

    public string $phone = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:140'],
            'mayor' => ['required', 'string', 'max:80'],
            'cnpj' => ['nullable', 'regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/'],
            'state_registration' => ['nullable', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:120'],
            'number' => ['required', 'string', 'max:10'],
            'neighborhood' => ['required', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
            'zip_code' => ['required', 'regex:/^\d{5}-\d{3}$/'],
            'phone' => ['required', 'string', 'min:14', 'max:15'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'nome da prefeitura',
            'mayor' => 'prefeito(a)',
            'cnpj' => 'CNPJ',
            'state_registration' => 'inscrição estadual',
            'address' => 'endereço',
            'number' => 'número',
            'neighborhood' => 'bairro',
            'city' => 'cidade',
            'zip_code' => 'CEP',
            'phone' => 'telefone',
        ];
    }

    public function setCityHall(CityHall $cityHall): void
    {
        $this->cityHall = $cityHall;

        $this->fill(array_map(
            fn ($value) => (string) $value,
            $cityHall->only(array_keys($this->rules())),
        ));
    }

    public function save(): CityHall
    {
        $data = $this->validate();

        $this->cityHall ??= new CityHall;
        $this->cityHall->fill($data)->save();

        return $this->cityHall;
    }
}
