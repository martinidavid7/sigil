@props(['title', 'subtitle' => null])

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mb-0 text-muted small">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="mt-3 mt-sm-0">{{ $actions }}</div>
    @endisset
</div>
