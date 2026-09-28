@props(['enabled'])

<span @class(['badge', 'badge-success' => $enabled, 'badge-secondary' => ! $enabled])>
    {{ $enabled ? 'Ativo' : 'Inativo' }}
</span>
