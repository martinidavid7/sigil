{{-- Campos da licitação (BiddingForm), usados na abertura e na edição. --}}
@use('App\Enums\BiddingType')

<div class="form-row">
    <x-sigil.input class="col-6 col-md-3" type="number" min="1" label="Número" wire:model="form.number" />
    <x-sigil.input class="col-6 col-md-3" type="number" min="2000" max="2100" label="Ano" wire:model="form.year" />
</div>

<div class="form-group">
    <label for="form_subject">Objeto</label>
    <textarea id="form_subject" rows="3" wire:model="form.subject"
        @class(['form-control', 'is-invalid' => $errors->has('form.subject')])
        placeholder="Descrição do que será contratado"></textarea>
    @error('form.subject')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <x-sigil.select class="col-md-6" label="Tipo" wire:model.live="form.type" placeholder="Selecione..."
        :options="BiddingType::options()" />
    <x-sigil.input class="col-md-6" label="Valor estimado (R$)" inputmode="decimal"
        wire:model.blur="form.estimated_value" x-mask:dynamic="$money($input, ',', '.')" placeholder="0,00" />
</div>

<x-sigil.select label="Modalidade" wire:model="form.bidding_mode_id" :options="$this->modeOptions" />

@if ($this->suggestedMode && $this->suggestedMode->id != $form->bidding_mode_id)
    <div class="alert alert-info d-flex align-items-center justify-content-between py-2 mt-n2">
        <span class="small">
            <i class="fas fa-lightbulb mr-1"></i>
            Pela faixa de valor, a modalidade indicada é <strong>{{ $this->suggestedMode->name }}</strong>.
        </span>
        <button type="button" class="btn btn-sm btn-info ml-2 text-nowrap" wire:click="applySuggestedMode">Usar</button>
    </div>
@endif
