<?php

namespace App\Livewire\Concerns;

trait Notifies
{
    /**
     * Exibe um toast no navegador (tratado pelo listener do layout admin).
     */
    protected function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('notify', message: $message, type: $type);
    }

    /**
     * Guarda o toast na sessão para exibi-lo após um redirecionamento.
     */
    protected function notifyAfterRedirect(string $message, string $type = 'success'): void
    {
        session()->flash('notify', ['message' => $message, 'type' => $type]);
    }
}
