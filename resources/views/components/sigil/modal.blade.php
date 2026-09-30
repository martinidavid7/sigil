{{--
    Modal controlado pelo Livewire: o componente pai decide quando exibi-lo
    através de `$show` e deve expor o método `closeModal()`.
--}}
@props(['title', 'show' => false, 'size' => 'lg'])

@once
    <style>
        /* Com o <form> envolvendo corpo e rodapé, só o corpo deve rolar e o rodapé fica sempre visível. */
        .modal-dialog-scrollable .modal-content > form {
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }
    </style>
@endonce

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
