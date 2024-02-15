@php
    $url = backpack_avatar_url($entry);
@endphp
<div class="d-flex align-items-center">
    @if ($url)
        <img class="rounded-circle avatar me-2" src="{{ $url }}" alt="{{ $entry->name ?? '' }}">
    @endif
    <strong>
        {{ $entry->name ?? '' }}
    </strong>
</div>
