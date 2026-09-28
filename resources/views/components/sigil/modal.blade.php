{{--
    Modal controlado pelo Livewire: o componente pai decide quando exibi-lo
    através de `$show` e deve expor o método `closeModal()`.
--}}
@props(['title', 'show' => false, 'size' => 'lg'])

@if ($show)
    <div class="modal d-block" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="modal-title"
        x-data x-on:keydown.escape.window="$wire.closeModal()">
        <div class="modal-dialog modal-{{ $size }} modal-dialog-scrollable" role="document">
            <div {{ $attributes->merge(['class' => 'modal-content']) }}>
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">{{ $title }}</h5>
                    <button type="button" class="close" wire:click="closeModal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
    <div class="modal-backdrop show"></div>
@endif
